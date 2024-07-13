# Real-Time Messenger Application

## Introduction

This is a real-time messenger application built using Laravel. The application allows users to communicate with each other in a peer-to-peer manner, supporting text, photos, and emojis. The real-time functionality is achieved using Pusher.

## Features

* **Peer-to-Peer Communication**: Users can communicate with each other in a real-time, peer-to-peer manner.

* **Supported Content Types**: The chat supports text, photos, and emojis.

* **Search Functionality**:Users can search for other users and start a conversation with them.

* **User Profile Management**: Users can update their profile information, including their profile image, name, and password.

* **Online Status Indicator**: When a user logs in, a green dot is displayed next to their profile, notifying other users that they are online.

* **Real-Time Updates**: The application uses Pusher to provide real-time updates, ensuring that messages are delivered instantly.

## Installation

1. **Clone the repository**:
      ```
      git clone https://github.com/yaman-shahbander-dev/messanger.git
      ```

2. **Navigate to the project directory**:
      ```
      cd messanger
      ```

3. **Install dependencies**:
      ```
      composer install
      ```

4. **Create a new .env file and configure the environment variables**:
      ```
      cp .env.example .env
      ```
      Open the .env file and update the following settings:
      
      * **DB_CONNECTION**: Set the database connection type (e.g., mysql, postgresql, sqlite).
      * **DB_HOST**, **DB_PORT**, **DB_DATABASE**, **DB_USERNAME**, **DB_PASSWORD**: Set the database connection details.

5. **Generate an application key**:
      ```
      php artisan key:generate
      ```

6. **Run the database migrations**:
      ```
      php artisan migrate
      ```

7. **Get Pusher Credentials (optional)**:
      ```
      PUSHER_APP_ID=
      PUSHER_APP_KEY=
      PUSHER_APP_SECRET=
      PUSHER_HOST=
      PUSHER_PORT=
      PUSHER_SCHEME=
      PUSHER_APP_CLUSTER=
      ```

8. **Start the development server**:
      ```
      php artisan serve
      ```

9. **Access the application**:

      Open your web browser and navigate to http://localhost/messenger

## Packages Used

The following packages have been used in this project:

* [php-flasher/flasher-notyf-laravel](https://github.com/php-flasher/flasher-notyf-laravel)
* [pusher/pusher-http-php](https://github.com/pusher/pusher-http-php)
