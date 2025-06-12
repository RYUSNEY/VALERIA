import pandas as pd
import json
from bs4 import BeautifulSoup
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service as ChromeService
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

def buscar_y_extraer_certificados(input_csv, output_json):
    """
    Automatiza la búsqueda de DNIs, extrae los detalles de cada certificado
    y guarda los resultados en un archivo JSON.
    """
    # --- Configuración de Selenium ---
    options = webdriver.ChromeOptions()
    #options.add_argument('--headless')
    #options.add_argument('--log-level=3')
    driver = webdriver.Chrome(service=ChromeService(ChromeDriverManager().install()), options=options)
    url = "https://vra-certificados.unap.edu.pe/busqueda"

    # --- Lectura del archivo CSV (CORRECCIÓN IMPORTANTE) ---
    # Leemos la columna 'dni' explícitamente como texto (str) para conservar ceros a la izquierda.
    try:
        df = pd.read_csv(input_csv, dtype={'dni': str})
        dnis = df['dni'].tolist()
    except FileNotFoundError:
        print(f"[!] Error: No se encontró el archivo '{input_csv}'.")
        driver.quit()
        return
    except KeyError:
        print(f"[!] Error: El archivo '{input_csv}' debe tener una columna llamada 'dni'.")
        driver.quit()
        return

    # Lista para almacenar todos los resultados
    resultados_finales = []

    print(f"[*] Iniciando la búsqueda para {len(dnis)} DNIs...")

    # --- Bucle para procesar cada DNI ---
    for dni in dnis:
        try:
            driver.get(url)
            driver.find_element(By.ID, "dni").send_keys(dni)
            driver.find_element(By.ID, "entrada").click()

            # Esperamos un máximo de 5 segundos a que la tabla cargue
            wait = WebDriverWait(driver, 5)
            wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, "tbody tr")))

            # Extraer el HTML del <tbody> y analizarlo con BeautifulSoup
            tbody_html = driver.find_element(By.TAG_NAME, "tbody").get_attribute('innerHTML')
            soup = BeautifulSoup(tbody_html, 'html.parser')
            
            # --- Extracción de datos estructurados ---
            certificados_encontrados = []
            filas = soup.find_all('tr') # Encontrar todas las filas <tr>

            for fila in filas:
                # Nos aseguramos de que la fila contenga las celdas <td> que esperamos
                if len(fila.find_all('td')) > 1:
                    nombre_cert = fila.find('h6').text.strip() if fila.find('h6') else 'No disponible'
                    
                    # Extraer código y tipo/estado de los spans
                    spans = fila.find_all('span', class_='badge')
                    codigo = 'No disponible'
                    tipo_o_estado = 'No disponible'
                    
                    if len(spans) > 0 and 'Código:' in spans[0].text:
                        codigo = spans[0].text.replace('Código:', '').strip()
                    if len(spans) > 1:
                        tipo_o_estado = spans[1].text.strip()

                    # Extraer el enlace "ver"
                    enlace_tag = fila.select_one('a.btn-info, a.btn-danger')
                    enlace = enlace_tag['href'] if enlace_tag else 'No disponible'

                    certificados_encontrados.append({
                        'nombre': nombre_cert,
                        'codigo': codigo,
                        'estado': tipo_o_estado,
                        'enlace': enlace
                    })
            
            # Guardar el resultado para este DNI
            resultados_finales.append({
                'dni': dni,
                'encontrado': True,
                'certificados': certificados_encontrados
            })
            print(f"[+] DNI {dni}: Encontrado ({len(certificados_encontrados)} certificados).")

        except Exception:
            # Si no se encuentran resultados
            resultados_finales.append({
                'dni': dni,
                'encontrado': False,
                'certificados': []
            })
            print(f"[-] DNI {dni}: No se encontraron certificados.")

    driver.quit()

    # --- Guardar los resultados en un archivo JSON ---
    with open(output_json, 'w', encoding='utf-8') as f:
        json.dump(resultados_finales, f, ensure_ascii=False, indent=4)

    print(f"\\n[+] ¡Proceso finalizado! Los resultados se han guardado en el archivo '{output_json}'")

# Ejecutar la función principal
if __name__ == "__main__":
    input_file = 'DNI Docentes.csv'
    output_file = 'resultados1.json'
    buscar_y_extraer_certificados(input_file, output_file) 