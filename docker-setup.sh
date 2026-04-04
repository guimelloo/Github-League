#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}🐳 Iniciando Docker Compose...${NC}"

# Copy .env.docker to .env if .env doesn't exist or is default
if [ ! -f .env ] || grep -q "DB_CONNECTION=sqlite" .env; then
    echo -e "${YELLOW}📋 Configurando arquivo .env para Docker...${NC}"
    cp .env.docker .env
fi

# Build and start containers
echo -e "${YELLOW}🔨 Construindo imagens Docker...${NC}"
docker-compose build

echo -e "${YELLOW}🚀 Iniciando containers...${NC}"
docker-compose up -d

# Wait for MySQL to be ready
echo -e "${YELLOW}⏳ Aguardando MySQL estar pronto...${NC}"
sleep 10

# Run Laravel setup
echo -e "${YELLOW}🔧 Executando setup do Laravel...${NC}"
docker-compose exec -T app php artisan key:generate
docker-compose exec -T app php artisan migrate --force
docker-compose exec -T app php artisan db:seed

# Install Node dependencies
echo -e "${YELLOW}📦 Instalando dependências Node.js...${NC}"
docker-compose exec -T node npm install

echo -e "${GREEN}✅ Setup completado!${NC}"
echo ""
echo -e "${GREEN}URLs disponíveis:${NC}"
echo -e "  🌐 App:            ${GREEN}http://localhost${NC}"
echo -e "  🗄️  MySQL:         ${GREEN}localhost:33061${NC}"
echo -e "  📦 Vite DevServer: ${GREEN}http://localhost:5173${NC}"
echo ""
echo -e "${YELLOW}Comandos úteis:${NC}"
echo -e "  ${GREEN}docker-compose logs -f app${NC}           # Ver logs da aplicação"
echo -e "  ${GREEN}docker-compose logs -f mysql${NC}         # Ver logs do MySQL"
echo -e "  ${GREEN}docker-compose exec app bash${NC}        # Entrar no shell do app"
echo -e "  ${GREEN}docker-compose down${NC}                 # Parar containers"
echo -e "  ${GREEN}docker-compose down -v${NC}              # Parar containers e remover volumes"
echo ""
