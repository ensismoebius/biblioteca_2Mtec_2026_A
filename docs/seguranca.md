# Revisão de Segurança
Essa revisão tem o objetivo de verificar os principais pontos de segurança da aplicação, seguindo os parâmetros definidos na Issue.
## Checklist
### 1. Mass assignment
 - Verificado o uso de `$fillable` nos models. **``✓``**
 - Nenhum model utiliza `$guarded = []`. **``✓``**

 
### 2. Proteção das rotas 
- Rotas sensíveis foram verificadas com `php artisan route:list -v`. **``✓``**
- Rotas de perfil utilizam o middleware `auth`. **``✓``**
- Rotas de autenticação e demais áreas sensíveis possuem os middlewares adequados. **``✓``**

 ### 3. SQL inseguro
 - Busca por `DB::raw, whereRaw` e `selectRaw` realizada no código da aplicação. **``✓``**
 - Nenhuma ocorrência foi encontrada fora dos diretórios de dependências ou arquivos gerados. **``✓``**

 ### 4. Exposição de senha
 - Busca por `USRSENHA` realizada no código da aplicação. **``✓``**
 - nenhuma ocorrência foi encontrada. **``✓``**

 ### 5. Dependências
 - Executado `composer audit`.**``✓``**
 - Nenhuma vulnerabilidade de segurança foi encontrada.**``✓``**

 ### 6. Testes de autorização
 - Adicionados testes para verificar tentativas de acesso direto às rotas protegidas do perfil.**``✓``**
 - Testes executados com sucesso.**``✓``**

 # Conclusão
 Todos os itens da checklist de segurança foram verificados e, até o momento da revisão, não foram encontradas vulnerabilidades abertas relacionadas aos parâmetros definidos pela issue.