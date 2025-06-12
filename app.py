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

# 👇 Aquí va tu función
@app.route('/buscar-dni', methods=['POST'])
def buscar_dni():
    try:
        dni = request.json.get('dni')
        resultado = scrape_vri(dni)
        return jsonify(resultado)
    except Exception as e:
        import traceback
        print("Error en /buscar-dni:", traceback.format_exc())
        return jsonify({'success': False, 'error': str(e)}), 500


def scrape_vri(dni):
    from selenium import webdriver
    from selenium.webdriver.common.by import By
    from selenium.webdriver.support.ui import WebDriverWait
    from selenium.webdriver.support import expected_conditions as EC
    from selenium.common.exceptions import TimeoutException
    from selenium.webdriver.chrome.options import Options

    import time

    chrome_options = Options()
    chrome_options.add_argument("--headless")  # si no quieres ver el navegador
    chrome_options.add_argument("--no-sandbox")
    chrome_options.add_argument("--disable-dev-shm-usage")

    driver = webdriver.Chrome(options=chrome_options)
    driver.get("https://vrintranet.unap.edu.pe/")

    try:
        wait = WebDriverWait(driver, 20)

        # Esperar que cargue el contenedor principal
        wait.until(EC.presence_of_element_located((By.ID, "dvBody")))

        # Buscar el botón "Buscar Certificados"
        boton = wait.until(EC.presence_of_element_located((
            By.XPATH, "//a[@onclick=\"loadWeb('dvBody','web/mnuBuscar')\"]"
        )))

        # Scroll hacia el botón
        driver.execute_script("arguments[0].scrollIntoView(true);", boton)

        # Esperar que sea clickeable y dar clic
        wait.until(EC.element_to_be_clickable((
            By.XPATH, "//a[@onclick=\"loadWeb('dvBody','web/mnuBuscar')\"]"
        ))).click()

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
