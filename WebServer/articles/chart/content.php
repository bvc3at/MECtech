<canvas id="canvas" width="500" height="500">Ваш браузер не поддерживает canvas</canvas>
<script>
var userData = {
    data1: {
        1: 100, 
        2: 300, 
        3: 141, 
        4: 40, 
        5: 385, 
        6: 266, 
        7: 200, 
        8: 130, 
        9: 365, 
        10: 276, 
        11: 251, 
        12: 186
    }
}


var draw = function() {
    var canvas = document.getElementById('canvas');
    if (canvas.getContext) { // проверяем наличие поддержки канваса у пользователя
        var context = canvas.getContext('2d'), // получаем context
            maxCountY = 400, // максимальное значение по OY. 400 Вт - макс документированная мощность МЭК
            maxCountX = 12, // максимальное значение по OX. Предполагается, что будет отображена информация за последние 12 часов
            x0 = y0 = 50, // начало координат
            OXname = "время, ч", // название OX
            OYname = "мощность, Вт", // название OY
            indentDigit = 3, // подстраивает цифровое значение координаты под ее линию. Неприятное магическое число, от которого я не могу избавиться, не навредив программе. 
            indentFont = 15, // поправка под размер шрифта. Неприятное магическое число, от которого я не могу избавиться, не навредив программе. 
            indentFontBigDigit = 20, // поправка под размер шрифта для 3-х значных чисел. Неприятное магическое число, от которого я не могу избавиться, не навредив программе.            
            width = canvas.width - x0 * 2,  // ширина координатной плоскости с исключением отступов
            height = canvas.height - y0 * 2, // высота координатной плоскости с исключением отступов
            widthClear = canvas.width - x0, // ширина координатной плоскости от начала координат
            heightClear = canvas.height - y0; // ширина координатной плоскости от начала координат

        var stepY = Math.round(height / maxCountY), // размер единичного отрезка по OY
            stepX = Math.round(width / maxCountX); // размер единичного отрезка по OX

        var restore = function() { // context.save() и context.restore() - не работали, как предполагалось, пришлось выдумывать костыли
            context.beginPath(); // сбрасываем соостояние холста до первоначального
            context.moveTo(x0, y0);
            context.lineWidth = 1; // в том числе размер
            context.strokeStyle = "#c0392b"; // и стиль пера  
            context.fillStyle = "#c0392b"; // и стиль заливки (используется только для текста)      
        }


        /* Рисуем плоскость в которой будем рисовать графики */
        restore(); // сбрасываем состояние холста

        context.lineTo(x0, height + y0);  // рисуем по OY
        context.lineTo(width + x0, height + y0);  // с той точки, на которой мы остановились ранее, рисуем по OX

        context.stroke(); // рисуем


        /* Рисуем разметку по OX */
        restore();

        for (var i = x0, m = 0; m < maxCountX; i += stepX) { // рисуем разметку по OX
            m ++;
            context.moveTo(i, height + y0);
            context.lineTo(i, height + y0 + indentFont);
            context.fillText(m, i + indentDigit, height + y0 + indentFont); // пишем цифры разметки
        }
        context.fillText(OXname, x0, height + y0 + indentFont*2); // пишем название OX

        /*  Рисуем разметку по OY и
            Рисуем график         */
        restore();

        context.lineWidth = 2; // увеличиваем размер пера
        context.strokeStyle = "#2980b9"; // и меняем цвет

        for (var data in userData) { 
            for (var n in userData[data]) { // благодаря такой вложенности, появляется возможность нарисовать несколько графиков
                var count = userData[data][n],
                    x = x0 + ((n - 1) * stepX), // координата текущей точки по OX
                    y = y0 + (height - count * stepY); // координата текущей точки по OY
                    context.lineTo(x, y); // рисуем линию от предыдущий точки (или начала координат) до текущей точки 
                context.fillText(count, x0 - indentFontBigDigit, y + indentDigit); // попутно ришем цифры разметки по OY
            }
        }
        context.fillText(OYname, 0, x0 - indentFont) // и название оси
        context.stroke(); // рисуем


        /* Рисуем вспомогательные клетки */
        restore(); // сбрасываем состояние холста
        context.lineWidth = 0.2; // делаем размер пера тоньше

        for (var x = x0; x < widthClear; x += stepX) { // рисуем вспомогательные клетки по OX.
            context.moveTo(x, x0);
            context.lineTo(x, widthClear);
        }
        for (var y = y0; y < heightClear; y += stepY * 10) { // рисуем вспомогательные клетки по OY. Тк если maxCountY ≈ height, то рисуя вспомогательные клетки полностью 
            context.moveTo(y0, y);                          // мы получим полностью закрашенное полотно. Чтобы избежать этого мы уменьшаем маштаб 
            context.lineTo(heightClear, y);                // в 10 раз ( stepY * 10 )
        }

        context.stroke(); // рисуем
    }
}
draw();
</script>