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
| `SESSION_DRIVER` | `file` |
| `CACHE_STORE` | `file` |
| `QUEUE_CONNECTION` | `sync` |
| `LOG_CHANNEL` | `stderr` |
| `RUN_SEED` | `true` *(yalnızca ilk deploy)* |

> **Önemli:** `DATABASE_URL` kullanıyorsanız `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` **tanımlamayın** — bağlantıyı bozar.

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

Deploy logunda `HATA:` veya `UYARI:` satırlarına bakın. En sık nedenler:
- `APP_KEY` eksik
- PostgreSQL servisi yok veya `DATABASE_URL` referansı yanlış
- Domain oluşturulmamış (`Unexposed service`)

Healthcheck `/health` (statik dosya, Laravel yuklenmeden yanit verir). Deploy Logs:
- `Sunucu baslatiliyor` görünmeli (migrate öncesi)
- `Migrate tamamlanamadi` → `DATABASE_URL` referansını ve Postgres servisinin çalıştığını kontrol edin
- Gerekirse `DB_SSLMODE=prefer` ekleyin (varsayılan artık `prefer`)

## 500 Server Error

1. **Variables** → şunları düzeltin:
   - `SESSION_DRIVER` = `file`
   - `CACHE_STORE` = `file`
   - `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` varsa **silin**
   - `DATABASE_URL` = Postgres referansı olmalı
   - `APP_URL` = `https://lojman-production.up.railway.app` (kendi domain'iniz)
2. Geçici olarak `APP_DEBUG` = `true` yapın → sayfayı yenileyin → hatayı okuyun → tekrar `false` yapın
3. **Console** sekmesinde:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```
4. **Redeploy** yapın
