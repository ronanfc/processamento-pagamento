# Processamento Pagamento


## Passo a passo para rodar o projeto
Clone o projeto
```sh
git clone https://github.com/ronanfc/processamento-pagamento.git
```
```sh
cd processamento-pagamento/
```


Crie o Arquivo .env
```sh
cp .env.example .env
```

Atualize essas variáveis de ambiente no arquivo .env
```dosini
ASAAS_API_KEY="sua-api-key-aqui"
ASAAS_API_URL="https://sandbox.asaas.com/api/v3/"
```
**Gerar uma chave pix no Asaas para que possa ser usada no projeto**

Suba os containers do projeto
```sh
docker-compose up -d
```

Acesse o container
```sh
docker-compose exec app bash
```

Instale as dependências do projeto
```sh
composer install
```

Gere a key do projeto Laravel
```sh
php artisan key:generate
```

Rodar a migrate do projeto
```sh
php artisan migrate
```

Rodar a seeder do projeto
```sh
php artisan db:seed
```

Instalar dependencias do projeto
```sh
npm install
```

Gerar build do projeto
```sh
npm run build
```

Acesse o projeto
[http://localhost:8000](http://localhost:8000)

Login e senha de administrador
```text
usuário: admin@api.com 
senha: admin
```
