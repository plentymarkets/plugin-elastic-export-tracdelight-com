
# ElasticExportTracdelightCOM plugin user guide

<div class="container-toc"></div>

## 1 Registering with Tracdelight.com

Tracdelight.com is a network for advertising agencies and publishing companies that focus on fashion and lifestyle.

## 2 Setting up the data format TracdelightCOM-Plugin in plentymarkets

The plugin Elastic Export is required to use this format.

Refer to the [Exporting data formats for price search engines](https://knowledge.plentymarkets.com/en/basics/data-exchange/exporting-data#30) page of the manual for further details about the individual format settings.

The following table lists details for settings, format settings and recommended item filters for the format **TracdelightCOM-Plugin**.
<table>
    <tr>
        <th>
            Settings
        </th>
        <th>
            Explanation
        </th>
    </tr>
    <tr>
        <td class="th" colspan="2">
            Settings
        </td>
    </tr>
    <tr>
        <td>
            Format
        </td>
        <td>
            Choose <b>TracdelightCOM-Plugin</b>.
        </td>        
    </tr>
    <tr>
        <td>
            Provisioning
        </td>
        <td>
            Choose <b>URL</b>.
        </td>        
    </tr>
    <tr>
        <td>
            File name
        </td>
        <td>
            The file name must have the ending <b>.csv</b> or <b>.txt</b> for Tracdelight.com to be able to import the file successfully.
        </td>        
    </tr>
    <tr>
        <td class="th" colspan="2">
            Item filter
        </td>
    </tr>
    <tr>
        <td>
            Active
        </td>
        <td>
            Choose <b>active</b>.
        </td>        
    </tr>
    <tr>
        <td>
            Markets
        </td>
        <td>
            Choose one or multiple order referrers. The chosen order referrer has to be active at the variation for the item to be exported.
        </td>        
    </tr>
    <tr>
        <td class="th" colspan="2">
            Format settings
        </td>
    </tr>
    <tr>
        <td>
            Order referrer
        </td>
        <td>
            Choose the order referrer that should be assigned during the order import.
        </td>        
    </tr>
    <tr>
        <td>
            Preview text
        </td>
        <td>
            This option is not relevant for this format.
        </td>        
    </tr>
    <tr>
        <td>
            Offer price
        </td>
        <td>
            This option is not relevant for this format.
        </td>        
    </tr>
    <tr>
        <td>
            VAT note
        </td>
        <td>
            This option is not relevant for this format.
        </td>        
    </tr>
</table>

## 3 Overview of available columns

<table>
    <tr>
        <th>
			Column name
		</th>
		<th>
			Explanation
		</th>
    </tr>
    <tr>
		<td>
			Artikelnummer
		</td>
		<td>
		    <b>Required</b><br>
		    <b>Content:</b> The <b>SKU</b> of the variation.
		</td>        
	</tr>
    <tr>
		<td>
			Produkttitel
		</td>
		<td>
		    <b>Required</b><br>
			<b>Content:</b> According to the format setting <b>Item name</b>.
		</td>        
	</tr>
	<tr>
		<td>
			Bild-URL
		</td>
		<td>
		    <b>Required</b><br>
		    <b>Content:</b> URL of the image according to the format setting <b>Image</b>. Variation images are prioritized over item images.
		</td>        
	</tr>
	<tr>
		<td>
			Deeplink
		</td>
		<td>
		    <b>Required</b><br>
		    <b>Content:</b> The <b>URL path</b> of the variation depending on the chosen <b>client</b> in the format settings.
		</td>        
	</tr>
	<tr>
		<td>
			Produkt-Kategorie
		</td>
		<td>
		    <b>Required</b><br>
		    <b>Content:</b> The name of the <b>category</b>.
		</td>        
	</tr>
	<tr>
		<td>
			Produkt-Beschreibung
		</td>
		<td>
		    <b>Required</b><br>
		    <b>Content:</b> According to the format setting <b>Description</b>.
		</td>        
	</tr>
	<tr>
		<td>
			Preis
		</td>
		<td>
		    <b>Required</b><br>
			<b>Content:</b> The <b>sales price</b> of the variation.
		</td>
	</tr>
	<tr>
		<td>
			Währung
		</td>
		<td>
		    <b>Required</b><br>
		    <b>Content:</b> The ISO code of the <b>currency</b> of the price.
		</td>        
	</tr>
	<tr>
		<td>
			Marke
		</td>
		<td>
		    <b>Required</b><br>
		    <b>Content:</b> The <b>name of the manufacturer</b> of the item. The <b>external name</b> in the menu <b>Settings » Items » Manufacturer</b> will be preferred if existing.
		</td>        
	</tr>
	<tr>
		<td>
			Versandkosten
		</td>
		<td>
		    <b>Required</b><br>
		    <b>Content:</b> According to the format setting <b>Shipping costs</b>.
		</td>        
	</tr>
	<tr>
		<td>
			Geschlecht
		</td>
		<td>
		    <b>Required</b><br>
			<b>Content:</b> The value of an attribute, with an attribute link for <b>tracdelight</b> to <b>Geschlecht</b>. As an alternative the value of a property, of the type <b>Text</b> or <b>Selection</b>, that is linked to <b>Tracdelight » Geschlecht</b> can also be used.
		</td>        
	</tr>
	<tr>
		<td>
			Grundpreis
		</td>
		<td>
		    <b>Required</b><br>
			<b>Content:</b> The <b>base price information</b> in the format "price / unit". (Example: 10,00 EUR / kilogram)
		</td>        
	</tr>
	<tr>
		<td>
			Streichpreis
		</td>
		<td>
		    <b>Content:</b> If the <b>RRP</b> is activated in the format setting and is higher than the <b>sales price</b>, the <b>RRP</b> will be exported.
		</td>        
	</tr>
	<tr>
		<td>
			Lieferzeit
		</td>
		<td>
			<b>Content:</b>The <b>item availability</b> under <b>Settings » Item » Item availability</b> or the translation according to the format setting <b>Item availability</b>.
		</td>        
	</tr>
	<tr>
		<td>
			Produktstamm-ID
		</td>
		<td>
		    <b>Content:</b> The <b>item ID</b> of the variation.
		</td>        
	</tr>
	<tr>
		<td>
			EAN
		</td>
		<td>
			<b>Content:</b> According to the format setting <b>Barcode</b>.
		</td>        
	</tr>
	<tr>
		<td>
			Bild2-URL
		</td>
		<td>
			<b>Content:</b> The image URL. Variation images are prioritized over item images.
		</td>        
	</tr>
	<tr>
		<td>
			Bild3-URL
		</td>
		<td>
			<b>Content:</b> The image URL. Variation images are prioritized over item images.
		</td>        
	</tr>
	<tr>
		<td>
			Bild4-URL
		</td>
		<td>
		    <b>Content:</b> The image URL. Variation images are prioritized over item images.
		</td>        
	</tr>
	<tr>
		<td>
			Bild5-URL
		</td>
		<td>
			<b>Content:</b> The image URL. Variation images are prioritized over item images.
		</td>        
	</tr>
	<tr>
		<td>
			Größe
		</td>
		<td>
			<b>Content:</b> The value of an attribute, with an attribute link for <b>tracdelight</b> to <b>Größe</b>. As an alternative the value of a property, of the type <b>Text</b>, <b>Selection</b>, <b>Integer</b> or <b>Real number</b> that is linked to <b>Tracdelight » Größe</b> can also be used.
		</td>        
	</tr>
	<tr>
		<td>
			Farbe
		</td>
		<td>
			<b>Content:</b> The value of an attribute, with an attribute link for <b>tracdelight</b> to <b>Farbe</b>. As an alternative the value of a property, of the type <b>Text</b> or <b>Selection</b>, that is linked to <b>Tracdelight » Farbe</b> can also be used.
		</td>        
	</tr>
	<tr>
		<td>
			Material
		</td>
		<td>
            <b>Content:</b> The value of an attribute, with an attribute link for <b>tracdelight</b> to <b>Material</b>. As an alternative the value of a property, of the type <b>Text</b> or <b>Selection</b>, that is linked to <b>Tracdelight » Material</b> can also be used.
		</td>        
	</tr>
</table>

## 4 License

This project is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE.- find further information in the [LICENSE.md](https://github.com/plentymarkets/plugin-elastic-export-tracdelight-com/blob/master/LICENSE.md).
