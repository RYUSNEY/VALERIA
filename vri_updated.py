import pandas as pd
import json
from bs4 import BeautifulSoup
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service as ChromeService
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

def buscar_en_vriunap_corregido():
    """
    Automatiza la búsqueda de DNIs en el portal de vriunap.pe, extrae los detalles
    de cada certificado y guarda los resultados en un archivo JSON.
    (Versión corregida para el selector del botón de búsqueda).
    """
    # --- Configuración de Selenium ---
    options = webdriver.ChromeOptions()
    #options.add_argument('--headless')
    #options.add_argument('--log-level=3')
    driver = webdriver.Chrome(service=ChromeService(ChromeDriverManager().install()), options=options)
    
    url = "https://vriunap.pe/cursos/"

    # --- Lectura del archivo CSV ---
    try:
        df = pd.read_csv('DNI Docentes.csv', dtype={'dni': str})
        dnis = df['dni'].tolist()
    except FileNotFoundError:
        print("Error: No se encontró el archivo 'DNI Docentes.csv'.")
        driver.quit()
        return
    except KeyError:
        print("Error: El archivo 'documentos.csv' debe tener una columna llamada 'dni'.")
        driver.quit()
        return

    resultados_finales = []
    
    print(f"Iniciando la búsqueda en vriunap.pe para {len(dnis)} DNIs...")

    for dni in dnis:
        try:
            driver.get(url)
            wait = WebDriverWait(driver, 10)

            # 1. Hacer clic en la opción "Buscar" del menú
            menu_buscar = wait.until(EC.element_to_be_clickable((By.XPATH, "//a[@onclick=\"loadWeb('dvBody','web/mnuBuscar')\"]")))
            menu_buscar.click()
            
            # 2. Esperar e introducir el DNI
            input_dni = wait.until(EC.presence_of_element_located((By.NAME, "eldni")))
            input_dni.send_keys(dni)
            
            # 3. Hacer clic en el botón de búsqueda (SELECTOR CORREGIDO)
            # Se busca un botón que contenga el texto "Buscar" en su interior.
            boton_buscar = driver.find_element(By.XPATH, "//button[@type='submit' and contains(., 'Buscar')]")
            boton_buscar.click()
            
            # 4. Esperar a que la tabla de resultados cargue
            wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, "tbody tr")))
            
            # 5. Extraer el HTML del <tbody> y analizarlo
            tbody_html = driver.find_element(By.TAG_NAME, "tbody").get_attribute('innerHTML')
            soup = BeautifulSoup(tbody_html, 'html.parser')
            
            # 6. Extraer los datos de cada fila
            certificados_encontrados = []
            filas = soup.find_all('tr')
            
            for fila in filas:
                celdas = fila.find_all('td')
                if len(celdas) > 4:
                    nombre_curso = celdas[1].text.strip()
                    tipo = celdas[2].text.strip()
                    codigo_vri = celdas[3].text.strip()
                    
                    # Extraer enlace de verificación (botón azul)
                    enlace_info_tag = fila.select_one('a.btn-info')
                    enlace_verificacion = enlace_info_tag['href'] if enlace_info_tag else 'No disponible'
                    
                    # Extraer enlace adicional (botón naranja)
                    enlace_warning_tag = fila.select_one('a.btn-warning')
                    enlace_adicional = enlace_warning_tag['href'] if enlace_warning_tag else 'No disponible'

                    certificados_encontrados.append({
                        'nombre_curso': nombre_curso,
                        'tipo': tipo,
                        'codigo': codigo_vri,
                        'enlace': enlace_verificacion
                    })
            
            if certificados_encontrados:
                resultados_finales.append({
                    'dni': dni,
                    'encontrado': True,
                    'certificados': certificados_encontrados
                })
                print(f"DNI {dni}: Encontrado ({len(certificados_encontrados)} certificados).")
            else:
                raise Exception("Tabla encontrada pero vacía.")

        except Exception:
            resultados_finales.append({
                'dni': dni,
                'encontrado': False,
                'certificados': []
            })
            print(f"DNI {dni}: No se encontraron certificados.")

    driver.quit()

    # --- Guardar los resultados en un archivo JSON ---
    output_filename = 'resultados_vriunap.json'
    with open(output_filename, 'w', encoding='utf-8') as f:
        json.dump(resultados_finales, f, ensure_ascii=False, indent=4)

    print(f"\n¡Proceso finalizado! Los resultados se han guardado en el archivo '{output_filename}'")

if __name__ == "__main__":
    buscar_en_vriunap_corregido() 