# Proyecto Laravel

Este es un proyecto de Laravel. Sigue los siguientes pasos para configurarlo e instalarlo correctamente.

## Requisitos

- PHP >= 8.2.9
- Composer
- MySQL

## Instalación

1. Clona el repositorio:
    ```bash
    git clone https://github.com/usuario/proyecto-laravel.git
    cd proyecto-laravel
    ```

2. Instala las dependencias de Composer:
    ```bash
    composer install
    ```

3. Copia el archivo `.env.example` a `.env`:
    ```bash
    cp .env.example .env
    ```

4. Genera la clave de la aplicación:
    ```bash
    php artisan key:generate
    ```

5. Configura el archivo `.env` con las siguientes variables:

    ```ini
    # Configuración de la base de datos
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nombre_de_tu_base_de_datos
    DB_USERNAME=tu_usuario_de_base_de_datos
    DB_PASSWORD=tu_contraseña_de_base_de_datos

    # Configuración del mail
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS=hello@example.com
    MAIL_FROM_NAME="${APP_NAME}"

    # Configuración de Fixer API
    FIXER_API_KEY=tu_api_key_de_fixer
    ```

6. Ejecuta las migraciones de la base de datos:
    ```bash
    php artisan migrate --seed
    ```

7. Inicia el servidor de desarrollo:
    ```bash
    php artisan serve
    ```

8. Accede a la api a traves de  `http://127.0.0.1:8000`.


## Configuración de Fixer API

Regístrate en [Fixer](https://fixer.io/) para obtener una API Key y configúrala en el archivo `.env`:

```ini
FIXER_API_KEY=tu_api_key_de_fixer
