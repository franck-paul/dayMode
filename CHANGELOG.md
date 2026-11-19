# Changelog

## 9.0 — 2026-11-08

- Fix archive list of posts for a single day
- Switch to SqlStatement
- DC 2.40+
- Use getter methods rather than widget properties as far as possible
- Ensure $params exists and is an array
- Cope with RTL
- Enforces some verification before registering module

## 8.0 — 2026-08-03

- Use App::url()-&gt;isType() where it is relevant
- Avoid using magic _get(), _set() methods of MetaRecord (2.39+)
- Avoid using magic methods for user preferences and blog settings (2.39+)
- Forgot this one
- Use MetaRecord::strField(), MetaRecord::intField() and MetaRecord::boolField() whenever is possible

## 7.5 — 2026-05-15

- Add support of glaz template set
- Harmonize usage of App::blogWorkspace()::NS_... and App::userWorkspace()::WS_... (and use short version of constant name for types)

## 7.4 — 2026-04-10

- DA -&gt; DC

## 7.3 — 2025-10-11

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 7.2 — 2025-09-12

- Replace static call to Ctx by context() — thanks JcDenis for advice

## 7.1 — 2025-09-07

- DC 2.36 compliance

## 7.0 — 2025-09-06

- DC 2.36 compliance

## 6.3 — 2025-08-05

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 6.2 — 2025-08-02

- Do not depend on dcProxyV1, it's better

## 6.1.2 — 2025-06-09

- Don't disable plugin in Prepend, too soon to check It will prevent to switch activation in Backend

## 6.1.1 — 2025-06-08

- Use the right method for previous item ;-)

## 6.1 — 2025-06-01

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 6.0 — 2025-04-16

- Use TemplateHelper for template blocks/values DC 2.34+

## 5.3.1 — 2025-03-11

- Avoid registering day archive URL if plugin not enabled

## 5.3 — 2025-02-26

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 5.2 — 2024-09-08

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 5.1.1 — 2023-11-28

- Fix type cast, closes #4, thanks @Philippe-dev

## 5.1 — 2023-10-27

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 5.0 — 2023-10-25

- Simplify template dir management
- Use template()-&gt;appendPath(…) rather than -&gt;setPath(-&gt;getPath(), …)
- Avoid magic method as far as possible
- Use 2.28 Config methods
- 2.28 compliance

## 4.0 — 2023-08-23

- Dotclear 2.27 compliance, PHP 8.1+

## 3.0.1 — 2023-06-27

- Add local static $init variable

## 3.0 — 2023-06-12

- Switch to namespace
- Prepare 2.26

## 2.4 — 2023-01-07

- Fix archive day mode

## 2.3.1 — 2023-01-02

- Cope with no args in URL, fixes #1

## 2.3 — 2022-12-22

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 2.2 — 2022-12-08

- on Archives only option comes back

## 2.1 — 2022-12-08

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 2.0 — 2022-12-07

- Changelog
- 2.24 compliance
- 1.1-RC6 from Pep and contributors

## 1.1-RC6 - 30-10-2017

- suppression summary
- ajout css
- modifs html

## 1.1-RC5 - 19-07-2015

- ajout breadcrumb
- modifs tpl
- dc2.8 requis

## 1.1-RC4 - 22-04-2015

- modif url support
- modif tpl (validation html5)

## 1.1-RC3 - 03-02-2015

- widget : ajout combo affichage + hors ligne + content_only + class
- modifs locales
- ajout tpl currywurst/mustek
- html5
- dc2.7 requis
