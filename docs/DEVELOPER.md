# Developer Info

## Models

### Achievement

| Attribute      | Type | Description |
|----------------|------|-------------|
| `id`           |||
| `guid`         |||
| `name`         |||
| `title`        |||
| `description`  |||
| `achievements` |||
| `status`       |||
| `visibility`   |||
| `created_at`   |||
| `created_by`   |||
| `updated_at`   |||
| `updated_by`   |||

### GameCenter

### Game

| Attribute      | Type           | Description                   |
|----------------|----------------|-------------------------------|
| `id`           | int            |                               |
| `guid`         | GUID           |                               |
| `name`         | string         | Unique ID of the Game Module  |
| `title`        | string         | Descriptive Title of the Game |
| `description`  | string         | Description                   |
| `achievements` | Achievements[] | List of Achievements          |
| `genre`        | string         | Genre of the Game             |
| `status`       | enum           |                               |
| `visibility`   | enum           |                               |
| `created_at`   | DateTime       |                               |
| `created_by`   | User ID        |                               |
| `updated_at`   | DateTime       |                               |
| `updated_by`   | User ID        |                               |

### Leaderboard

### Player

### Score

---

## Migrations

The following command executed in the `protected` directory will create a new migration into the `protected/humhub/migrations` folder:

```shell
php yii migrate/create gamecenter_initial
```

## I18N

### Generate message files

```shell
php yii message/extract-module gamecenter
```

## Testing

```shell
cd protected/humhub/tests/codeception/bin
php yii migrate/up --includeModuleMigrations=1 --interactive=0
php yii migrate/up --includeModuleMigrations=1 --interactive=0 -p '@gamecenter/migrations'
```

## API-Documentation

```shell
docker run --rm -v "$(pwd):/data" phpdoc/phpdoc --force --validate
```
