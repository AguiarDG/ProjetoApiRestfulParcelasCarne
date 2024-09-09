<p align="center">
  <a href="" rel="noopener">
 <img width=200px height=200px src="https://i.imgur.com/6wj0hh6.jpg" alt="Project logo"></a>
</p>

<h3 align="center">Desafio Técnico</h3>

<div align="center">

[![Status](https://img.shields.io/badge/status-active-success.svg)]()
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](/LICENSE)

</div>

---

<p align="center"> API para criação de Carnês e suas respectivas parcelas
    <br> 
</p>

## 📝 Sumário

- [Sobre](#about)
- [Início](#getting_started)
- [Como utilizar](#usage)
- [Técnologias](#built_using)
- [Autor](#authors)

## 🧐 Sobre <a name = "about"></a>

Projeto feito para um Desafio Técnico, para criação de parcelamento de cobranças via Carnês

## 🏁 Início <a name = "getting_started"></a>

### Pré requisitos

Precisa ter o Docker e Docker Compose instalado na maquina, o SGDB da sua preferencia e um software para realizar as requisições. É possivel utilizar o browser para executar os comandos, porem os software são mais praticos para visualizar o retorno.

```
- Docker
- Docker Compose
- SGDB (Sistema de gerenciamento de banco de dados, eu utilizei o HeidiSQL, mas você pode utilizar o da sua preferencia)
- Postman / Insomnia
```

## 🎈 Como utilizar <a name="usage"></a>

- Via terminal, acessar o projeto baixado e subir as imagens/containers com docker compose
```
docker-compose up -d
```
- Caso necessário, realizar o build novamente segue o comando que faz o build e sobe as imagens/containers
```
docker-compose up --build -d
```
- Duplique e renomeie o arquivo <b>.env.example</b> para <b>.env</b> e altere para as suas configurações;
- Rode os comandos abaixo para gerar o APP_KEY do arquivo <b>.env</b>
```
# Entra no container via terminal
docker-compose exec app bash

# Gera o APP_KEY
php artisan key:generate
```

- Executar o migrate (Cria as tabelas)
```
docker-compose exec app php artisan migrate
```
- Executar o seeders (Popula as tabelas)
```
docker-compose exec app php artisan db:seed
```

Agora o projeto já esta configurado e a API acessivel no seu localhost:
```
http://localhost/api
```

Abaixo os Endpoints disponiveis:
```
** Endpoints **
GET - /carnes - Exibe todas os Carnês e Parcelas;
GET - /carnes/{id} - Exibe as parcelas de um determinado carnê (trocando {id} pelo id do carnê);
POST - /carnes - Cadastra um carnê e gera suas parcelas*;

*(deve enviar um json no body, exemplo:
    {
        "valor_total": 10,
        "qtd_parcelas": 5,
        "data_primeiro_vencimento": "2024-08-01",
        "periodicidade": "mensal", (ou semanal)
        "valor_entrada": 0.10 (opcional)
    }
)*

```

Obs.: Caso o banco de dados não seja criado automaticamente, adicionei ao projeto <b>db.sql</b>

** Lembrando que esse projeto é somente para acessar via API, o frontend não foi desenvolvido.

## ⛏️ Técnologias <a name = "built_using"></a>

- [Docker](https://docker.com/) - Docker
- [Docker Compose](https://docs.docker.com/compose/) - Docker Compose
- [Composer](https://getcomposer.org) - Composer
- [MySQL](https://www.mysql.com/) - Database
- [Laravel 11](https://laravel.com/) - Server Framework

## ✍️ Autor <a name = "authors"></a>

- [@AguiarDG](https://github.com/AguiarDG)
