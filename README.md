# Notes
You can create test user via php artisan db:seed --class=AdminUserSeeder

Example payload for manual testing `api/send`:
```json
[
    {
        "recipient": "johndoe@mailsac.com",
        "subject": "hey there",
        "body": "<b>Bold text here</b>",
        "attachments": [
            {
                "fileName": "file.txt",
                "fileContent": "MTIzNDU2"
            },
            {
                "fileName": "file2.txt",
                "fileContent": "MTIzNDU2Nzg="
            }
        ]
    },
    {
        "recipient": "johndoe@mailsac.com",
        "subject": "hey there2",
        "body": "<b>Bold text there</b>"
    }
]
```
