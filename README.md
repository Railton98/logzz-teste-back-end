# Teste prático para Back-End 
***

> Credênciais (email: `test@example.com` senha: `password`)

O projeto está baseado em Docker, utilizando o [Laravel Sail](https://laravel.com/docs/10.x/sail)

## Instruções para rodar o projeto em Ambiente Local

- Clonar repositório:
```bash
git clone https://github.com/Railton98/logzz-teste-back-end
cd logzz-teste-back-end
```

- Instalar dependências (Para ambientes baseados em `Docker` com [Laravel Sail](https://laravel.com/docs/10.x/sail#installing-composer-dependencies-for-existing-projects)):
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

- Criar arquivo `.env` e rodar `key:generate`:
```bash
cp .env.example .env

# Com Laravel Sail
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail npm i
./vendor/bin/sail npm run build
```

- Executar migrations e rodar projeto:
```bash
# Com Laravel Sail
./vendor/bin/sail artisan migrate --seed
```
> A opção `--seed` irá criar um `usuário padrão`.

- Acesse [http://localhost](http://localhost), faça login com essas credênciais(email: `test@example.com` senha: `password`) e utilize as funcionalidades pelos menus `Categories` e `Products`.


## Importar Produtos e Categorias da API externa
Comando:
```bash
# Todos os produtos e categorias
./vendor/bin/sail artisan products:import
# Produto especifico
./vendor/bin/sail artisan products:import --id=1
```

## Testes Automatizados
Esse projeto utiliza o [PestPHP](https://pestphp.com/) como framework de testes.

- Para executar os testes:
```bash
# Com Laravel Sail
./vendor/bin/sail artisan test
# ou
./vendor/bin/sail pest
```
