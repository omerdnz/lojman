# Railway — Lojman Kurulum (Zorunlu Variables)

Railway panelinde **lojman (web)** servisi → **Variables** → aşağıdakileri ekleyin.

PostgreSQL servisini aynı projeye ekleyin: **+ New → Database → PostgreSQL**

## Zorunlu değişkenler

| Değişken | Değer |
|----------|--------|
| `APP_NAME` | `Lojman Yönetim` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | `php artisan key:generate --show` çıktısı |
| `APP_URL` | Domain oluşturduktan sonra `https://....up.railway.app` |
| `APP_LOCALE` | `tr` |
| `DB_CONNECTION` | `pgsql` |
| `DATABASE_URL` | `${{Postgres.DATABASE_URL}}` *(PostgreSQL servis adınıza göre)* |
| `SESSION_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `QUEUE_CONNECTION` | `database` |
| `LOG_CHANNEL` | `stderr` |
| `RUN_SEED` | `true` *(yalnızca ilk deploy)* |

> `DATABASE_URL` Railway PostgreSQL eklentisinden otomatik gelir. Referans: Variables → **Add Reference** → Postgres → `DATABASE_URL`

## Domain (zorunlu — "Unexposed" kaldırmak için)

1. Web servisi → **Settings** → **Networking**
2. **Generate Domain** tıklayın
3. `APP_URL` değişkenini bu URL ile güncelleyin
4. Redeploy

## Build ayarı

**Settings → Build → Custom Build Command** alanı **BOŞ** olmalı.

## İlk deploy sonrası

1. Site açılınca `RUN_SEED` değişkenini silin
2. `admin` / `123456` şifresini değiştirin

## Sorun giderme

Deploy logunda `HATA:` satırlarına bakın. En sık nedenler:
- `APP_KEY` eksik
- PostgreSQL servisi yok veya `DATABASE_URL` referansı yanlış
- Domain oluşturulmamış (`Unexposed service`)
