from flask import Flask, request, jsonify
from bs4 import BeautifulSoup
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service as ChromeService
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager
from flask_cors import CORS

app = Flask(__name__)
CORS(app)  # para permitir solicitudes desde Vue

@app.route('/buscar-dni', methods=['POST'])
def buscar_dni():
    data = request.get_json()
    dni = data.get('dni')

    resultados = scrape_vri(dni)
    return jsonify(resultados)

def scrape_vri(dni):
    options = webdriver.ChromeOptions()
    options.add_argument('--headless')  # Quitar si quieres ver navegador
    options.add_argument('--no-sandbox')
    options.add_argument('--disable-dev-shm-usage')

    driver = webdriver.Chrome(service=ChromeService(ChromeDriverManager().install()), options=options)

    try:
        driver.get("https://vriunap.pe/cursos/")
        wait = WebDriverWait(driver, 50)

        menu_buscar = wait.until(EC.element_to_be_clickable((By.XPATH, "//a[@onclick=\"loadWeb('dvBody','web/mnuBuscar')\"]")))
        menu_buscar.click()

        input_dni = wait.until(EC.presence_of_element_located((By.NAME, "eldni")))
        input_dni.send_keys(dni)

        boton_buscar = driver.find_element(By.XPATH, "//button[@type='submit' and contains(., 'Buscar')]")
        boton_buscar.click()

        wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, "tbody tr")))
        tbody_html = driver.find_element(By.TAG_NAME, "tbody").get_attribute('innerHTML')
        soup = BeautifulSoup(tbody_html, 'html.parser')

        certificados = []
        for fila in soup.find_all('tr'):
            celdas = fila.find_all('td')
            if len(celdas) > 4:
                certificados.append({
                    'nombre_curso': celdas[1].text.strip(),
                    'tipo': celdas[2].text.strip(),
                    'codigo': celdas[3].text.strip(),
                    'enlace': fila.select_one('a.btn-info')['href'] if fila.select_one('a.btn-info') else 'No disponible'
                })

        return {'dni': dni, 'encontrado': bool(certificados), 'certificados': certificados}
        
    except Exception as e:
        import traceback
        print(f"Error para DNI {dni}:")
        traceback.print_exc()
        return {'dni': dni, 'encontrado': False, 'certificados': []}

    finally:
        driver.quit()

if __name__ == '__main__':
    app.run(debug=True)
