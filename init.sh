docker-compose up --build -d
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose php artisan horizon:install
docker-compose php artisan telescope:install
docker-compose php artisan storage:link
docker-compose exec app php artisan optimize
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed

npm install --global yarn
yarn
yarn dev


