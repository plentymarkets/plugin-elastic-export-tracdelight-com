
# User Guide für das ElasticExportTracdelightCOM Plugin

<div class="container-toc"></div>

## 1 Bei Tracdelight.com registrieren

Tracdelight.com ist ein Netzwerk für Werbeagenturen und Verlage, das sich auf Fashion, Mode und Lifestyle fokussiert.

## 2 Datenformat TracdelightCOM-Plugin in plentymarkets einrichten

Um dieses Format nutzen zu können, benötigen Sie das Plugin Elastic Export.

Auf der Handbuchseite [Daten exportieren](https://www.plentymarkets.eu/handbuch/datenaustausch/daten-exportieren/#4) werden die einzelnen Formateinstellungen beschrieben.

In der folgenden Tabelle finden Sie Hinweise zu den Einstellungen, Formateinstellungen und empfohlenen Artikelfiltern für das Format **TracdelightCOM-Plugin**.
<table>
    <tr>
        <th>
            Einstellung
        </th>
        <th>
            Erläuterung
        </th>
    </tr>
    <tr>
        <td class="th" colspan="2">
            Einstellungen
        </td>
    </tr>
    <tr>
        <td>
            Format
        </td>
        <td>
            <b>TracdelightCOM-Plugin</b> wählen.
        </td>        
    </tr>
    <tr>
        <td>
            Bereitstellung
        </td>
        <td>
            <b>URL</b> wählen.
        </td>        
    </tr>
    <tr>
        <td>
            Dateiname
        </td>
        <td>
            Der Dateiname muss auf <b>.csv</b> oder <b>.txt</b> enden, damit Tracdelight.com die Datei erfolgreich importieren kann.
        </td>        
    </tr>
    <tr>
        <td class="th" colspan="2">
            Artikelfilter
        </td>
    </tr>
    <tr>
        <td>
            Aktiv
        </td>
        <td>
            <b>Aktiv</b> wählen.
        </td>        
    </tr>
    <tr>
        <td>
            Märkte
        </td>
        <td>
            Eine oder mehrere Auftragsherkünfte wählen. Die gewählten Auftragsherkünfte müssen an der Variante aktiviert sein, damit der Artikel exportiert wird.
        </td>        
    </tr>
    <tr>
        <td class="th" colspan="2">
            Formateinstellungen
        </td>
    </tr>
    <tr>
        <td>
            Auftragsherkunft
        </td>
        <td>
            Die Auftragsherkunft wählen, die beim Auftragsimport zugeordnet werden soll.
        </td>        
    </tr>
    <tr>
        <td>
            Vorschautext
        </td>
        <td>
            Diese Option ist für dieses Format nicht relevant.
        </td>        
    </tr>
    <tr>
        <td>
            Angebotspreis
        </td>
        <td>
            Diese Option ist für dieses Format nicht relevant.
        </td>        
    </tr>
    <tr>
        <td>
            MwSt.-Hinweis
        </td>
        <td>
            Diese Option ist für dieses Format nicht relevant.
        </td>        
    </tr>
</table>


## 3 Übersicht der verfügbaren Spalten

<table>
    <tr>
        <th>
            Spaltenbezeichnung
        </th>
        <th>
            Erläuterung
        </th>
    </tr>
    <tr>
		<td>
			Artikelnummer
		</td>
		<td>
		    <b>Pflichtfeld</b><br>
		    <b>Inhalt:</b> Die <b>SKU</b> der Variante.
		</td>        
	</tr>
    <tr>
		<td>
			Produkttitel
		</td>
		<td>
		    <b>Pflichtfeld</b><br>
			<b>Inhalt:</b> Entsprechend der Formateinstellung <b>Artikelname</b>.
		</td>        
	</tr>
	<tr>
		<td>
			Bild-URL
		</td>
		<td>
		    <b>Pflichtfeld</b><br>
			<b>Inhalt:</b> URL zu dem Bild gemäß der Formateinstellungen <b>Bild</b>. Variantenbilder werden vor Artikelbildern priorisiert.
		</td>        
	</tr>
	<tr>
		<td>
			Deeplink
		</td>
		<td>
		    <b>Pflichtfeld</b><br>
		    <b>Inhalt:</b> Der <b>URL-Pfad</b> des Artikels abhängig vom gewählten <b>Mandanten</b> in den Formateinstellungen.
		</td>        
	</tr>
	<tr>
		<td>
			Produkt-Kategorie
		</td>
		<td>
		    <b>Pflichtfeld</b><br>
		    <b>Inhalt:</b> Der Name der Kategorie.
		</td>        
	</tr>
	<tr>
		<td>
			Produkt-Beschreibung
		</td>
		<td>
		    <b>Pflichtfeld</b><br>
		    <b>Inhalt:</b> Entsprechend der Formateinstellung <b>Beschreibung</b>.
		</td>        
	</tr>
	<tr>
		<td>
			Preis
		</td>
		<td>
		    <b>Pflichtfeld</b><br>
			<b>Inhalt:</b> Hier steht der <b>Verkaufspreis</b>.
		</td>
	</tr>
	<tr>
		<td>
			Währung
		</td>
		<td>
			<b>Pflichtfeld</b><br>
            <b>Inhalt:</b> Der ISO-Code der <b>Währung</b> des Preises.
		</td>        
	</tr>
	<tr>
		<td>
			Marke
		</td>
		<td>
		    <b>Pflichtfeld</b><br>
		    <b>Inhalt:</b> Der <b>Name des Herstellers</b> des Artikels. Der <b>Externe Name</b> unter <b>Einstellungen » Artikel » Hersteller</b> wird bevorzugt, wenn vorhanden.
		</td>        
	</tr>
	<tr>
		<td>
			Versandkosten
		</td>
		<td>
		    <b>Pflichtfeld</b><br>
		    <b>Inhalt:</b> Entsprechend der Formateinstellung <b>Versandkosten</b>.
		</td>        
	</tr>
	<tr>
		<td>
			Geschlecht
		</td>
		<td>
			<b>Pflichtfeld</b><br>
            <b>Inhalt:</b> Der Wert eines Attributs, bei dem die Attributverknüpfung für <b>tracdelight</b> mit <b>Geschlecht</b> gesetzt wurde. Alternativ der Wert eines Merkmals vom Typ <b>Text</b> oder <b>Auswahl</b>, das mit <b>Tracdelight.com » Geschlecht</b> verknüpft wurde.
		</td>        
	</tr>
	<tr>
		<td>
			Grundpreis
		</td>
		<td>
		    <b>Pflichtfeld</b><br>
			<b>Inhalt:</b> Der berechnete Grundpreis bezogen auf die <b>Grundpreis Einheit</b>.
		</td>        
	</tr>
	<tr>
		<td>
			Streichpreis
		</td>
		<td>
			<b>Inhalt:</b> Der <b>Verkaufspreis</b> der Variante. Wenn der <b>UVP</b> in den Formateinstellungen aktiviert wurde und höher ist als der Verkaufspreis, wird dieser hier eingetragen.
		</td>        
	</tr>
	<tr>
		<td>
			Lieferzeit
		</td>
		<td>
			<b>Inhalt:</b> Der <b>Artikelverfügbarkeit</b> unter <b>Einstellungen » Artikel » Artikelverfügbarkeit</b> oder die Übersetzung gemäß der Formateinstellung <b>Artikelverfügbarkeit</b>.
		</td>        
	</tr>
	<tr>
		<td>
			Produktstamm-ID
		</td>
		<td>
		    <b>Inhalt:</b> Die <b>Artikel-ID</b> der Variante.
		</td>        
	</tr>
	<tr>
		<td>
			EAN
		</td>
		<td>
			<b>Inhalt:</b> Entsprechend der Formateinstellung <b>Barcode</b>.
		</td>        
	</tr>
	<tr>
		<td>
			Bild2-URL
		</td>
		<td>
			<b>Inhalt:</b> URL des Bildes. Variantenbiler werden vor Artikelbildern priorisiert.
		</td>        
	</tr>
	<tr>
		<td>
			Bild3-URL
		</td>
		<td>
		    <b>Inhalt:</b> URL des Bildes. Variantenbiler werden vor Artikelbildern priorisiert.
		</td>        
	</tr>
	<tr>
		<td>
			Bild4-URL
		</td>
		<td>
			<b>Inhalt:</b> URL des Bildes. Variantenbiler werden vor Artikelbildern priorisiert.
		</td>        
	</tr>
	<tr>
		<td>
			Bild5-URL
		</td>
		<td>
		    <b>Inhalt:</b> URL des Bildes. Variantenbiler werden vor Artikelbildern priorisiert.
		</td>        
	</tr>
	<tr>
		<td>
			Größe
		</td>
		<td>
			<b>Inhalt:</b> Der Wert eines Attributs, bei dem die Attributverknüpfung für <b>tracdelight</b> mit <b>Größe</b> gesetzt wurde. Alternativ der Wert eines Merkmals vom Typ <b>Text</b>, <b>Auswahl</b>, <b>ganze Zahl</b> oder <b>Kommazahl</b>, das mit <b>Tracdelight.com » Größe</b> verknüpft wurde.
		</td>        
	</tr>
	<tr>
		<td>
			Farbe
		</td>
		<td>
			<b>Inhalt:</b> Der Wert eines Attributs, bei dem die Attributverknüpfung für <b>tracdelight</b> mit <b>Farbe</b> gesetzt wurde. Alternativ der Wert eines Merkmals vom Typ <b>Text</b> oder <b>Auswahl</b>, das mit <b>Tracdelight.com » Farbe</b> verknüpft wurde.
		</td>        
	</tr>
	<tr>
		<td>
			Material
		</td>
		<td>
			<b>Inhalt:</b> Der Wert eines Attributs, bei dem die Attributverknüpfung für <b>tracdelight</b> mit <b>Material</b> gesetzt wurde. Alternativ der Wert eines Merkmals vom Typ <b>Text</b> oder <b>Auswahl</b>, das mit <b>Tracdelight.com » Material</b> verknüpft wurde.
		</td>        
	</tr>
</table>

## 4 Lizenz

Das gesamte Projekt unterliegt der GNU AFFERO GENERAL PUBLIC LICENSE – weitere Informationen finden Sie in der [LICENSE.md](https://github.com/plentymarkets/plugin-elastic-export-tracdelight-com/blob/master/LICENSE.md).
