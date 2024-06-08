const express = require('express');
const puppeteer = require('puppeteer');

const app = express();

app.get('/productos', async (req, res) => {
    try {
        const browser = await puppeteer.launch();
        const page = await browser.newPage();
        await page.goto('https://m.shein.com.mx/new/Men-Bottoms-sc-002160653.html?adp=20376447&pageFrom=sidecat&productsource=sidecat-%E9%80%89%E5%93%81%E9%A1%B5-Hombre-Novedades-002160653&src_identifier=fc%3DHombre%60sc%3DNovedades%60tc%3D0%60oc%3DBottoms%60ps%3Dtab05navbar01menu01dir4%60jc%3DitemPicking_002160653&src_module=sidecat&src_tab_page_id=page_goods_detail1715876810037&srctype=category&userpath=Comprar%3EHombre%3ECOMMON_NAVIGATE_COMPONENT_1%3ECOMMON_CATEGORY_COMPONENT_1%3EMen-Bottoms');

        await page.waitForSelector('#product-list-v2 > div:nth-child(4) > div > div.waterfall');

        const productos = await page.evaluate(() => {
            const productos = [];
            const productElements = document.querySelectorAll('#product-list-v2 > div:nth-child(4) > div > div.waterfall .product-card');
            productElements.forEach(item => {
                const nombre = item.querySelector('.product-card__goods-title-container').textContent.trim();
                const precio = item.querySelector('.product-card__camel-case-price').textContent.trim();
                productos.push({ nombre, precio });
            });
            return productos;
        });

        await browser.close();
        res.json(productos);
    } catch (error) {
        console.error('Error al ejecutar Puppeteer:', error);
        res.status(500).json({ error: 'Error al obtener los productos de Shein' });
    }
});

const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Servidor API iniciado en http://localhost:${PORT}`);
});