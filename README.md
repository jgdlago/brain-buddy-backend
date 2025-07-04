# BrainBuddy Back-end

**Laravel 12 • PHP 8.3 • PostgreSQL • Docker + Sail**

---

## 1. Pré‑requisitos
- Docker instalado
- Windows - WSL Configurado (opcional)

## 2. Instalação

```bash
git clone https://github.com/jgdlago/brain-buddy-backend.git
cd brain-buddy-backend

cp .env.example .env

./vendor/bin/sail up -d
./vendor/bin/sail composer install
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

## 3. Acesso

- API: http://localhost
- Documentação (Scramble): http://localhost/docs
- MailHog: http://localhost:8025

## 4. Comandos úteis

```bash
./vendor/bin/sail up -d      # start
./vendor/bin/sail down       # stop
./vendor/bin/sail composer   # Composer
./vendor/bin/sail logs       # logs
```

