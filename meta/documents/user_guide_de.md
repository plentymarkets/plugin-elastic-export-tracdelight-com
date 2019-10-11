
# User Guide für das ElasticExportTracdelightCOM Plugin

<div class="container-toc"></div>

## 1 Bei Tracdelight.com registrieren

Tracdelight.com ist ein Netzwerk für Werbeagenturen und Verlage, das sich auf Fashion, Mode und Lifestyle fokussiert.

## 2 Das Format TracdelightCOM-Plugin in plentymarkets einrichten

Mit der Installation dieses Plugins erhältst du das Exportformat **TracdelightCOM-Plugin**, mit dem du Daten über den elastischen Export zu Tracdelight.com überträgst. Um dieses Format für den elastischen Export nutzen zu können, installiere zunächst das Plugin **Elastic Export** aus dem plentyMarketplace, wenn noch nicht geschehen. 

Sobald beide Plugins in deinem System installiert sind, kann das Exportformat **TracdelightCOM-Plugin** erstellt werden. Weitere Informationen findest du auf der Handbuchseite [Elastischer Export](https://knowledge.plentymarkets.com/daten/daten-exportieren/elastischer-export).

Neues Exportformat erstellen:

1. Öffne das Menü **Daten » Elastischer Export**.
2. Klicke auf **Neuer Export**.
3. Nimm die Einstellungen vor. Beachte dazu die Erläuterungen in Tabelle 1.
4. **Speichere** die Einstellungen.<br/>
→ Eine ID für das Exportformat **TracdelightCOM-Plugin** wird vergeben und das Exportformat erscheint in der Übersicht **Exporte**.

In der folgenden Tabelle findest du Hinweise zu den einzelnen Formateinstellungen und empfohlenen Artikelfiltern für das Format **TracdelightCOM-Plugin**.

| **Einstellung**                                     | **Erläuterung** |
| :---                                                | :--- |
| **Einstellungen**                                   | |
| **Name**                                            | Name eingeben. Unter diesem Namen erscheint das Exportformat in der Übersicht im Tab **Exporte**. |
| **Typ**                                             | Typ **Artikel** aus der Dropdown-Liste wählen. |
| **Format**                                          | **TracdelightCOM-Plugin** wählen. |
| **Limit**                                           | Zahl eingeben. Wenn mehr als 9999 Datensätze an Tracdelight übertragen werden sollen, wird die Ausgabedatei wird für 24 Stunden nicht noch einmal neu generiert, um Ressourcen zu sparen. Wenn mehr mehr als 9999 Datensätze benötigt werden, muss die Option **Cache-Datei generieren** aktiv sein. |
| **Cache-Datei generieren**                          | Häkchen setzen, wenn mehr als 9999 Datensätze an Tracdelight übertragen werden sollen. Um eine optimale Perfomance des elastischen Exports zu gewährleisten, darf diese Option bei maximal 20 Exportformaten aktiv sein. |
| **Bereitstellung**                                  | **URL** wählen. |
| **Dateiname**                                       | Der Dateiname muss auf **.csv** oder **.txt** enden, damit Tracdelight.com die Datei erfolgreich importieren kann. |
| **Token, URL**                                      | Wenn unter **Bereitstellung** die Option **URL** gewählt wurde, auf **Token generieren** klicken. Der Token wird dann automatisch eingetragen. Die URL wird automatisch eingetragen, wenn unter **Token** der Token generiert wurde. |
| **Artikelfilter**                                   | |
| **Artikelfilter hinzufügen**                        | Artikelfilter aus der Dropdown-Liste wählen und auf **Hinzufügen** klicken. Standardmäßig sind keine Filter voreingestellt. Es ist möglich, alle Artikelfilter aus der Dropdown-Liste nacheinander hinzuzufügen.<br/> **Varianten** = **Alle übertragen**, **Nur Hauptvarianten übertragen**, oder **Keine Hauptvarianten übertragen** wählen.<br/> **Märkte** = Eine oder mehrere Auftragsherkünfte wählen. Die gewählten Auftragsherkünfte müssen an der Variante aktiviert sein, damit der Artikel exportiert wird.<br/> **Währung** = Währung wählen.<br/> **Kategorie** = Aktivieren, damit der Artikel mit Kategorieverknüpfung übertragen wird. Es werden nur Artikel, die dieser Kategorie zugehören, übertragen.<br/> **Bild** = Aktivieren, damit der Artikel mit Bild übertragen wird. Es werden nur Artikel mit Bildern übertragen.<br/> **Mandant** = Mandant wählen.<br/> **Bestand** = Wählen, welche Bestände exportiert werden sollen.<br/> **Markierung 1 - 2** = Markierung wählen.<br/> **Hersteller** = Einen, mehrere oder ALLE Hersteller wählen.<br/> **Aktiv** = Nur aktive Varianten werden übertragen. |
| **Formateinstellungen**                             | |
| **Produkt-URL**                                     | Wählen, ob die URL des Artikels oder der Variante an das Preisportal übertragen wird. Varianten-URLs können nur in Kombination mit dem Ceres Webshop übertragen werden. |
| **Mandant**                                         | Mandant wählen. Diese Einstellung wird für den URL-Aufbau verwendet. |
| **URL-Parameter**                                   | Suffix für die Produkt-URL eingeben, wenn dies für den Export erforderlich ist. Die Produkt-URL wird dann um die eingegebene Zeichenkette erweitert, wenn weiter oben die Option **übertragen** für die Produkt-URL aktiviert wurde. |
| **Auftragsherkunft**                                | Die Auftragsherkunft wählen, die beim Auftragsimport zugeordnet werden soll. Die Produkt-URL wird um die gewählte Auftragsherkunft erweitert, damit die Verkäufe später analysiert werden können. |
| **Marktplatzkonto**                                 | Marktplatzkonto aus der Dropdown-Liste wählen. |
| **Sprache**                                         | Sprache aus der Dropdown-Liste wählen. |
| **Artikelname**                                     | **Name 1**, **Name 2** oder **Name 3** wählen. Die Namen sind im Tab **Texte** eines Artikels gespeichert.<br/> Im Feld **Maximale Zeichenlänge (def. Text)** optional eine Zahl eingeben, wenn Tracdelight eine Begrenzung der Länge des Artikelnamen beim Export vorgibt. |
| **Vorschautext**                                    | Diese Option ist für dieses Format nicht relevant. |
| **Beschreibung**                                    | Wählen, welcher Text als Beschreibungstext übertragen werden soll.<br/> Im Feld **Maximale Zeichenlänge (def. Text)** optional eine Zahl eingeben, wenn Tracdelight eine Begrenzung der Länge der Beschreibung beim Export vorgibt.<br/> Option **HTML-Tags entfernen** aktivieren, damit die HTML-Tags beim Export entfernt werden.<br/> Im Feld **Erlaubte HTML-Tags, kommagetrennt (def. Text)** optional die HTML-Tags eingeben, die beim Export erlaubt sind. Wenn mehrere Tags eingegeben werden, mit Komma trennen. |
| **Zielland**                                        | Zielland aus der Dropdown-Liste wählen. |
| **Barcode**                                         | ASIN, ISBN oder eine EAN aus der Dropdown-Liste wählen. Der gewählte Barcode muss mit der oben gewählten Auftragsherkunft verknüpft sein. Andernfalls wird der Barcode nicht exportiert. |
| **Bild**                                            | **Position 0** oder **Erstes Bild** wählen, um dieses Bild zu exportieren.<br/> **Position 0** = Ein Bild mit der Position 0 wird übertragen.<br/> **Erstes Bild** = Das erste Bild wird übertragen. |
| **Bildposition des Energieetiketts**                | Diese Option ist für dieses Format nicht relevant. |
| **Bestandspuffer**                                  | Diese Option ist für dieses Format nicht relevant. |
| **Bestand für Varianten ohne Bestandsbeschränkung** | Diese Option ist für dieses Format nicht relevant. |
| **Bestand für Varianten ohne Bestandsführung**      | Diese Option ist für dieses Format nicht relevant. |
| **Währung live umrechnen**                          | Aktivieren, damit der Preis je nach eingestelltem Lieferland in die Währung des Lieferlandes umgerechnet wird. Der Preis muss für die entsprechende Währung freigegeben sein. |
| **Verkaufspreis**                                   | Brutto- oder Nettopreis aus der Dropdown-Liste wählen. |
| **Angebotspreis**                                   | Diese Option ist für dieses Format nicht relevant. |
| **UVP**                                             | Aktivieren, um den UVP zu übertragen. |
| **Versandkosten**                                   | Aktivieren, damit die Versandkosten aus der Konfiguration übernommen werden. Wenn die Option aktiviert ist, stehen in den beiden Dropdown-Listen Optionen für die Konfiguration und die Zahlungsart zur Verfügung.<br/> Option **Pauschale Versandkosten übertragen** aktivieren, damit die pauschalen Versandkosten übertragen werden. Wenn diese Option aktiviert ist, muss im Feld darunter ein Betrag eingegeben werden. |
| **MwSt.-Hinweis**                                   | Diese Option ist für dieses Format nicht relevant. |
| **Artikelverfügbarkeit**                            | Option **überschreiben** aktivieren und in die Felder **1** bis **10**, die die ID der Verfügbarkeit darstellen, Artikelverfügbarkeiten eintragen. Somit werden die Artikelverfügbarkeiten, die im Menü **Einrichtung » Artikel » Verfügbarkeit** eingestellt wurden, überschrieben. |

_Tab. 1: Einstellungen für das Datenformat **TracdelightCOM-Plugin**_

## 3 Verfügbare Spalten der Exportdatei

| **Spaltenbezeichnung** | **Erläuterung** |
| :---                   | :--- |
| Artikelnummer          | **Pflichtfeld**<br/> Die SKU der Variante. |
| Produkttitel           | **Pflichtfeld**<br/> Entsprechend der Formateinstellung **Artikelname**. |
| Bild-URL               | **Pflichtfeld**<br/> URL zu dem Bild gemäß der Formateinstellungen **Bild**. Variantenbilder werden vor Artikelbildern priorisiert. |
| Deeplink               | **Pflichtfeld**<br/> Der URL-Pfad des Artikels abhängig vom gewählten **Mandanten** in den Formateinstellungen. |
| Produkt-Kategorie      | **Pflichtfeld**<br/> Der Name der Kategorie. |
| Produkt-Beschreibung   | **Pflichtfeld**<br/> Entsprechend der Formateinstellung **Beschreibung**. |
| Preis                  | **Pflichtfeld**<br/> Hier steht der Verkaufspreis. |
| Währung                | **Pflichtfeld**<br/> Der ISO-Code der Währung des Preises. |
| Marke                  | **Pflichtfeld**<br/> Der Name des Herstellers des Artikels. Der **Externe Name** unter **Einrichtung » Artikel » Hersteller** wird bevorzugt, wenn vorhanden. |
| Versandkosten          | **Pflichtfeld**<br/> Entsprechend der Formateinstellung **Versandkosten**. |
| Geschlecht             | **Pflichtfeld**<br/> Der Wert eines Attributs, bei dem die Attributverknüpfung für Tracdelight mit **Geschlecht** gesetzt wurde. Alternativ der Wert eines Merkmals vom Typ **Text** oder **Auswahl**, das mit **Tracdelight.com » Geschlecht** verknüpft wurde. |
| Grundpreis             | **Pflichtfeld**<br/> Der berechnete Grundpreis bezogen auf die Grundpreiseinheit. |
| Streichpreis           | Der Verkaufspreis der Variante. Wenn der **UVP** in den Formateinstellungen aktiviert wurde und höher ist als der Verkaufspreis, wird dieser hier eingetragen. |
| Lieferzeit             | Die Artikelverfügbarkeit unter **Einrichtung » Artikel » Artikelverfügbarkeit** oder die Übersetzung gemäß der Formateinstellung **Artikellverfügbarkeit**. |
| Produktstamm-ID        | Die Artikel-ID der Variante. |
| EAN                    | Entsprechend der Formateinstellung **Barcode**. |
| Bild2-URL              | URL des Bildes. Variantenbilder werden vor Artikelbildern priorisiert. |
| Bild3-URL              | URL des Bildes. Variantenbilder werden vor Artikelbildern priorisiert. |
| Bild4-URL              | URL des Bildes. Variantenbilder werden vor Artikelbildern priorisiert. |
| Bild5-URL              | URL des Bildes. Variantenbilder werden vor Artikelbildern priorisiert. |
| Größe                  | Der Wert eines Attributs, bei dem die Attributverknüpfung für Tracdelight mit **Größe** gesetzt wurde. Alternativ der Wert eines Merkmals vom Typ **Text**, **Auswahl**, **ganze Zahl** oder **Kommazahl**, das mit **Tracdelight.com » Größe** verknüpft wurde. |
| Farbe                  | Der Wert eines Attributs, bei dem die Attributverknüpfung für Tracdelight mit **Farbe** gesetzt wurde. Alternativ der Wert eines Merkmals vom Typ **Text** oder **Auswahl**, das mit **Tracdelight.com » Farbe** verknüpft wurde. |
| Material               | Der Wert eines Attributs, bei dem die Attributverknüpfung für Tracdelight mit **Material** gesetzt wurde. Alternativ der Wert eines Merkmals vom Typ **Text** oder **Auswahl**, das mit **Tracdelight.com » Material** verknüpft wurde. |

## 4 Lizenz

Das gesamte Projekt unterliegt der GNU AFFERO GENERAL PUBLIC LICENSE – weitere Informationen findest du in der [LICENSE.md](https://github.com/plentymarkets/plugin-elastic-export-tracdelight-com/blob/master/LICENSE.md).
