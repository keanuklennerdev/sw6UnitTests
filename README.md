# Shopware 6 Unit Tests
### Was ist ein Unit Test?
Ein Unit-Test ist eine Art von Softwaretest, der darauf abzielt, einzelne Einheiten einer Softwareanwendung isoliert zu testen.
Eine "Einheit" bezieht sich typischerweise auf die kleinste testbare Komponente einer Software, wie z.B. eine Funktion, eine Methode oder eine Klasse.

### Hauptaspekte / Vorteile von Unit Tests
#### Funktionalitätssicherheit:
  - Sie stellen sicher, dass diese Einheiten die erwarteten Ergebnisse liefern und gemäß den Spezifikationen arbeiten.

#### Fehlererkennung und -prävention:
  - Die dienen dazu, Fehler frühzeitig im Entwicklungsprozess zu erkennen und zu verhindern. Sie helfen, potenzielle Probleme aufzudecken, bevor sie zu größeren Problemen in der Anwendung führen.

#### Refaktorisierungssicherheit:
  - Wenn Entwickler Code refaktorisieren, können Unit-Tests sicherstellen, dass die Funktionalität wie gewünscht erhalten bleibt. Sie geben den Entwicklern das Vertrauen, dass ihre Änderungen keine unerwarteten Nebenwirkungen haben.

#### Dokumentation:
  - Unit-Tests dienen auch als Form der Dokumentation für den beabsichtigten Gebrauch von Code. Sie erklären, wie die Funktion aussieht, was die Wartung und Weiterentwicklung erleichtert.

#### Verbesserung der Codequalität:
  - Das Schreiben von Unit-Tests fördert eine gute Programmierpraxis und hilft dabei, sauberen, wartbaren und robusten Code zu schreiben.

### Was sollte getestet werden?
Im Falle eines Shopware 6 Shops sollten hier die Einheiten eines Plugins getestet werden. Beispielsweise die funktionalität eines Services.

### Was gibt es zu beachten?
- Tests im Nachhinein zu schreiben ist in vielen Fällen kaum möglich.
- Tests für einen dekorierten Shopware Service sind teilweise sehr komplex und dadurch sehr aufwendig, da oft Parameter mit "unsauberen" Typen verwendet werden, die alle gemockt werden müssen.
- Es sollten Standards für die Pfade usw. für Tests in Shopware Plugins erstellt werden, da sich diese je nach plugin namespace struktur leicht ändern. Der Weg aus der Dokumentation funktioniert teilweise nicht oder ist sehr unübersichtlich.
- Man benötigt das Flex Template um Tests ausführen zu können.

### Best Practises?
Best Practises werden sich wahrscheinlich erst richtig herausstellen, sobald man anfängt heufiger Tests zu schreiben.
Man kann aber schon einiges aus der PHPUnit Dokumentation entnehmen, wie zum Beispiel das arbeiten mit DataProvidern.

### Unit-Tests im Shopware 6 Umfeld

### Wie werden Unit-Tests in Shopware 6 ausgeführt:
Flex Template hinzufügen
```bash
composer require --dev dev-tools
```
Alle Tests im Plugin ausführen
```bash
./vendor/bin/phpunit --configuration="custom/static-plugins/ConneCustomerGroupExtensions"
```
Bestimmten Test ausführen
```bash
./vendor/bin/phpunit --configuration="custom/static-plugins/ConneCustomerGroupExtensions" --filter CurrencyFilterTest
```
Abteile ausführen
```bash
./vendor/bin/phpunit --configuration="custom/plugins/SwagBasicExample" --testsuite "Testsuite"
```
