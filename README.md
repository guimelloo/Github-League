# GitHub League

Aplicação Laravel 13 com Inertia.js e Vue.js 3, configurada com Docker.

## 🚀 Início Rápido

### 1️⃣ **Iniciar o Projeto**

```bash
cd /Users/gui/projects/github-league
docker-compose up -d
```

**Pronto!** A aplicação está rodando em: **http://localhost**

### 2️⃣ **Verificar Status**

```bash
docker-compose ps
```

Você verá:
- ✅ `github_league_app` (Laravel)
- ✅ `github_league_mysql` (Banco)
- ✅ `github_league_nginx` (Servidor web)
- ✅ `github_league_node` (Vite)

### 3️⃣ **Parar a Aplicação**

```bash
docker-compose down
```

---

## 🗄️ Conectar Banco no DBeaver

### Passo 1: Abrir DBeaver

1. Abra o **DBeaver**
2. Clique em **New Database Connection** (ou `Ctrl+Shift+N`)
3. Selecione **MySQL**
4. Clique em **Next**

### Passo 2: Configurar Conexão

Preencha com estes dados:

| Campo | Valor |
|-------|-------|
| **Server Host** | `localhost` |
| **Port** | `33061` |
| **Database** | `github_league` |
| **Username** | `laravel` |
| **Password** | `laravel_secret` |

### Passo 3: Testar Conexão

1. Clique em **Test Connection**
2. Se aparecer verde ✅, clique em **Finish**

### Passo 4: Explorar Banco

Na aba esquerda, você verá:
```
Databases
└── github_league
    ├── Tables
    │   ├── users
    │   ├── migrations
    │   ├── sessions
    │   └── ...
    └── Views
```

---

## 📋 URLs Disponíveis

| Serviço | URL |
|---------|-----|
| 🌐 **Aplicação** | http://localhost |
| 📦 **Vite DevServer** | http://localhost:5173 |
| 🗄️ **MySQL** | localhost:33061 |

---

## 🔧 Comandos Úteis

### Rodar Migrações

```bash
docker-compose exec app php artisan migrate
```

### Popular Banco com Dados

```bash
docker-compose exec app php artisan db:seed
```

### Limpar Cache

```bash
docker-compose exec app php artisan cache:clear
```

### Ver Logs

```bash
docker-compose logs -f app
```

### Entrar no Console Laravel (Tinker)

```bash
docker-compose exec app php artisan tinker
```

---

## 📁 Estrutura do Projeto

```
.
├── app/                          # Código PHP
├── resources/
│   ├── js/
│   │   ├── app.js              # Principal Vue
│   │   └── Pages/              # Componentes Inertia
│   │       ├── Welcome.vue
│   │       ├── Auth/
│   │       │   ├── Login.vue
│   │       │   ├── Register.vue
│   │       │   └── ...
│   │       └── ...
│   ├── views/
│   │   └── app.blade.php       # Template principal
│   └── css/
├── routes/
│   ├── web.php                 # Rotas web
│   └── auth.php                # Rotas autenticação
├── docker-compose.yml          # Configuração Docker
├── Dockerfile                  # Imagem PHP
└── vite.config.js              # Configuração Vite
```

---

## 🐛 Troubleshooting

### Tela Branca no Navegador

1. Abra **DevTools** (F12)
2. Vá para **Console**
3. Veja se há erros de JavaScript
4. Verifique aba **Network** se assets estão carregando

### Erro de Conexão MySQL

Certifique-se de que:
- O container MySQL está rodando: `docker-compose ps`
- Credenciais estão corretas no `.env`

### Limpar Tudo

```bash
docker-compose down -v
docker-compose up -d
```

---

## 🔐 Credenciais

### Banco de Dados

- **Host**: localhost:33061
- **Database**: github_league
- **Username**: laravel
- **Password**: laravel_secret

### MySQL Root

- **Username**: root
- **Password**: root

---

## 📚 Mais Informações

- [Documentação Docker](/DOCKER.md)
- [Documentação Laravel](https://laravel.com/docs)
- [Inertia.js](https://inertiajs.com/)
- [Vue.js 3](https://vuejs.org/)
