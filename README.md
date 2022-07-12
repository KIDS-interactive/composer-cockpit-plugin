# KIDS - Composer Cockpit Plugin #

Mit diesem Composer-Plugin ist es möglich, Cockpit CMS in seinem Composer-Projekt versionskontrolliert zu nutzen.

---

### Anforderungen ###

- Composer >= 2
- PHP >= 7.4

---

### Installation ###

- Ergänze oder erstelle eine `composer.json` mit folgender Konfiguration:
```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/KIDS-interactive/composer-cockpit-plugin.git"
        }
    ],
    "config": {
        "platform":{
            "ext-mongodb":"1.8"
        }
    }
}
```
- Installiere das Plugin: `composer require kids/cockpit-plugin` und bestätige dabei das Vertrauen in das Plugin mit `y`.
- Es wird eine `cockpit` Konfiguration in der `composer.json` ergänzt:
 - `app-groups`: Die App-Gruppen sind benamte Listen von Ordner-Pfaden, in welche Cockpit installiert werden soll.
 - `override-dir`: In diesem Ordner liegen die Dateien versionskontrolliert, welche in alle Cockpit-Installationen kopiert werden (z.B. Collections).
- Installiere Cockpit mit: `composer install-cockpit [app-group=default]` (der `app-group` Parameter ist optional)
- Ergänze die Installationsordner in der `.gitignore` deines Projektes!

---

### Versionskontrolle ###

- Wenn man Änderungen an Collections, Singeltons oder im `config`-Order macht, geschieht dies immer im Installationsordner.
- Will man diese Änderungen einchecken, muss man sie in den definierten `override-dir` kopieren und commiten.
