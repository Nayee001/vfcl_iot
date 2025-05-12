
# vFCL IoT Device Health Status Ecosystem

This repository hosts the web application component of the vFCL IoT Device Health Status Ecosystem. The application provides a command center for monitoring and managing virtualized Fault Current Limiter (vFCL) devices. It leverages Laravel as a backend framework, integrates with MQTT for secure IoT communications, and enables real-time monitoring, historical data analysis, and alert notifications for device health anomalies.

## Key Features

- **IoT Integration (MQTT):** Collect device health data from sensors and Raspberry Pi IoT endpoints.
- **Real-Time Monitoring:** Visualize device performance and receive alerts for unusual conditions.
- **Historical Data Review:** Access stored historical data to identify trends and potential issues.
- **Secure and Scalable:** Built with Laravel, ensuring a robust and maintainable codebase.

## Prerequisites

- **PHP (8.0+ recommended)**
- **Composer** (PHP dependency manager)
- **Database Server** (MySQL, PostgreSQL, or compatible)
- **Web Server** (Nginx, Apache, or Laravel’s built-in server)
- **MQTT Broker** (e.g., Mosquitto) accessible to the application and IoT devices

## Installation

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/Nayee001/vfcl_iot.git
   cd your-project
   ```

2. **Install Dependencies:**
   ```bash
   composer install
   ```

3. **Set Up Environment Variables:**
   Copy the example `.env` file and then edit it:
   ```bash
   cp .env.example .env
   ```
   
   Open `.env` in your preferred editor and update the following:

   - **Application Settings:**
     ```env
     APP_NAME="vFCL IOT Health"
     APP_ENV=local
     APP_URL=http://localhost
     ```
   
   - **Database Configuration:**
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=vfcl_db
     DB_USERNAME=root
     DB_PASSWORD=secret
     ```

   - **MQTT Settings:**
     Ensure your IoT devices and MQTT broker align with these settings.
     ```env
     MQTT_HOST=broker.example.com
     MQTT_PORT=1883
     MQTT_USERNAME=mqttuser
     MQTT_PASSWORD=mqttpass
     MQTT_TOPIC=vfcl/health/#
     ```

   Adjust these values to reflect your actual environment.

4. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```
   
   This command sets the `APP_KEY` in your `.env` file, ensuring encrypted data remains secure.

5. **Migrate the Database:**
   Create the necessary tables and optionally seed initial data:
   ```bash
   php artisan migrate --seed
   ```

6. **Start the Server:**
   Use Laravel’s built-in server for development:
   ```bash
   php artisan serve
   ```
   
   The application is now accessible at `http://localhost:8000` (or the URL you specified).

## MQTT Integration

Before integrating real devices, test your MQTT setup:

- **Broker Connectivity:** Confirm you can connect to `MQTT_HOST:MQTT_PORT`.
- **Test Messages:** Use a tool like `mosquitto_pub` to publish test messages to your topic.
  
When devices publish health metrics to the specified MQTT topic, the application listens, processes incoming data, and updates the database and dashboard in real-time.

## Production Deployment

For production:

- Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`.
- Serve via a stable web server (Nginx/Apache) configured for HTTPS.
- Ensure proper firewall rules and secure ports for MQTT and web access.
- Consider load balancing and caching (Redis) for high availability.

## Contributing

Contributions are welcome! Please fork the repository, create a feature branch, and submit a pull request. For significant changes, open an issue first to discuss what you’d like to add.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

This README provides instructions for setting up the Laravel application, configuring the `.env` file, and establishing the MQTT integration within the context of the vFCL IoT Device Health Status Ecosystem.

## Develop by Akshaykumar Nayee made with  ❤️
