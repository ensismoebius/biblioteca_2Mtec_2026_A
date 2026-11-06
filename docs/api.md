# API de Livros

Esta API disponibiliza operações de consulta de livros do sistema de biblioteca. Atualmente, é possível listar os livros cadastrados e consultar um livro específico.

## Autenticação

Todas as requisições para os endpoints de livros precisam enviar um token válido no cabeçalho `Authorization`:

```http
Authorization: Bearer SEU_TOKEN
```

**Exemplo usando cURL:**

```bash
curl -X GET http://localhost:8000/api/livros \
  -H "Accept: application/json" \
  -H "Authorization: Bearer SEU_TOKEN"
```

Sem um token válido, a API retorna `401 Unauthorized`.

## Listar livros

### Requisição

```http
GET /api/livros
```

**Exemplo:**

```bash
curl -X GET http://localhost:8000/api/livros \
  -H "Accept: application/json" \
  -H "Authorization: Bearer SEU_TOKEN"
```

### Resposta

A resposta é paginada:

```json
{
    "data": [
        {
            "codigo": 1,
            "titulo": "Quarta Asa",
            "isbn": "1234567890",
            "edicao": 1,
            "data_publicacao": "2026-10-01",
            "sinopse": "Guerras, Dragoes, politica e poderes. (Ugabuga)",
            "faixa_etaria": 18
        }
    ],
    "links": {
        "first": "http://localhost:8000/api/livros?page=1",
        "last": "http://localhost:8000/api/livros?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "per_page": 10,
        "to": 1,
        "total": 1
    }
}
```

Os campos internos `created_at` e `updated_at` não são expostos pela API.

## Consultar um livro

### Requisição

```http
GET /api/livros/{id}
```

**Exemplo:**

```bash
curl -X GET http://localhost:8000/api/livros/1 \
  -H "Accept: application/json" \
  -H "Authorization: Bearer SEU_TOKEN"
```

### Resposta

```json
{
    "data": {
        "codigo": 1,
        "titulo": "Quarta Asa",
        "isbn": "1234567890",
        "edicao": 1,
        "data_publicacao": "2026-10-01",
        "sinopse": "Guerras, Dragoes, politica e poderes. (Ugabuga)",
        "faixa_etaria": 18
    }
}
```

## Endpoints disponíveis

| Método | Endpoint           | Autenticação | Descrição                    |
| ------ | ------------------ | ------------ | ---------------------------- |
| GET    | `/api/livros`      | Bearer Token | Lista livros paginados       |
| GET    | `/api/livros/{id}` | Bearer Token | Consulta um livro específico |