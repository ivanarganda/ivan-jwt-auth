ivan/jwt-auth

⚡ Build secure PHP APIs in minutes, not hours.

Minimal, reusable and extensible JWT authentication API base for PHP projects.

🚀 Overview

ivan/jwt-auth is a lightweight API base designed to acelerar el desarrollo backend proporcionando un sistema de autenticación listo para usar.

Incluye un sistema de rutas simple, endpoints de autenticación y una interfaz de documentación integrada, permitiendo a los desarrolladores centrarse en construir funcionalidades en lugar de rehacer la lógica de autenticación desde cero.

Construido con una idea clara:

Automation + Reusability

✨ Features
🔐 Sistema de autenticación preparado para JWT
🧱 Estructura limpia y modular
🔀 Routing simple mediante .htaccess
🧾 Endpoints de registro y login
🌐 Comunicación basada en JSON
📄 Documentación interactiva integrada en "/"
⚡ Arquitectura plug & play
🧩 Fácil de extender
📦 Installation

git clone https://github.com/your-username/ivan-jwt-auth.git

cd ivan-jwt-auth

⚙️ Basic Setup
Configura tu servidor local (recomendado Apache)
Crea un VirtualHost apuntando a /api
Activa mod_rewrite
Configura la base de datos en core/config.php
Crea la tabla users
🌐 API Endpoints

Base URL:
http://ivan-jwt-auth.api

🔐 Register

POST /register

Body:
{
"name": "Ivan",
"email": "ivan@email.com
",
"password": "123456"
}

🔑 Login

POST /login

Response:
{
"status": true,
"message": "Login successful",
"token": "your_jwt_token_here"
}

📄 Documentation UI

Accediendo a:
http://ivan-jwt-auth.api/

Puedes:

Probar endpoints
Enviar requests
Ver respuestas
Entender la estructura de la API
🧠 How It Works
Todas las peticiones pasan por api/index.php
.htaccess transforma las URLs (/login, /register)
Un router simple resuelve el endpoint
Se carga el archivo correspondiente en /src
Cada endpoint gestiona su lógica
Se devuelve respuesta en JSON
🧩 Extending the API

Añadir nuevos endpoints es directo:

case 'profile':
require_once '../src/profile.php';
break;

Acceso:
http://ivan-jwt-auth.api/profile

🔒 Security
Uso de password_hash() para contraseñas
Preparado para JWT
Separación clara entre routing y lógica

Nota: Middleware JWT y rutas protegidas están en roadmap.

📁 Project Structure

api/
.htaccess
index.php

core/
config.php

src/
login.php
register.php
doc.php

vendor/
composer.json
README.md

🎯 Purpose

Este proyecto está diseñado para:

Evitar rehacer autenticación en cada proyecto
Servir como base rápida para APIs
Permitir escalabilidad futura
Convertirse en un paquete Composer reutilizable
🚧 Roadmap
JWT generation
JWT validation middleware
Protected routes
Profile endpoint
Role-based authentication
Refresh tokens
Composer package (ivan/jwt-auth)
💡 Philosophy

En lugar de escribir autenticación una y otra vez:

Build once → reuse everywhere

Este proyecto refleja una mentalidad enfocada en eficiencia, escalabilidad y automatización.

👨‍💻 Author

Ivan Gonzalez

📄 License

MIT License