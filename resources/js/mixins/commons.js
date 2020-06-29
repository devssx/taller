/* You can define here your global methods 
 *
 */
import { Printd } from "printd";

export default {
    methods: {
        getColor(index) {
            var palete = ['#409EFF', '#F56C6C', '#E6A23C', '#67C23A', '#909399',
                '#FFA07A', '#FF7F50', '#FFDAB9', '#9ACD32', '#40E0D0',
                '#87CEFA', '#EE82EE', '#FF69B4', '#F5DEB3', '#D2691E',
                '#FF99E6', '#CCFF1A', '#FF1A66', '#E6331A', '#33FFCC',
                '#66994D', '#B366CC', '#4D8000', '#B33300', '#CC80CC',
                '#66664D', '#991AFF', '#E666FF', '#4DB3FF', '#1AB399',
                '#E666B3', '#33991A', '#CC9999', '#B3B31A', '#00E680',
                '#4D8066', '#809980', '#E6FF80', '#1AFF33', '#999933',
                '#FF3380', '#CCCC00', '#66E64D', '#4D80CC', '#9900B3',
                '#E64D66', '#4DB380', '#FF4D4D', '#99E6E6', '#6666FF'];
            return palete[index];
        },
        saveObject(obj, name) {
            const json = JSON.stringify(obj);
            localStorage.setItem(name, json);
        },
        loadObject(name) {
            if (localStorage.getItem(name))
                return JSON.parse(localStorage.getItem(name));
            return null;
        },
        deleteObject(name, clear) {
            localStorage.removeItem(name);
            if (clear)
                localStorage.clear();
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(2);
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        fixNumber: function (n) {
            return n < 10 ? "0" + n : n;
        },
        toFixedDateFormat: function (dt, format) {
            if (!dt) dt = new Date();

            var yyyy = dt.getFullYear();
            var MM = this.fixNumber(dt.getMonth() + 1);
            var dd = this.fixNumber(dt.getDate());

            var hh = this.fixNumber(dt.getHours());
            var mm = this.fixNumber(dt.getMinutes());
            var ss = this.fixNumber(dt.getSeconds());

            switch (format) {
                case "yyyy-MM-dd":
                    return `${yyyy}-${MM}-${dd}`;
            }

            return `${yyyy}-${MM}-${dd} ${hh}:${mm}:${ss}`;
        },
        toFixedFormat(dt, format) {
            if (!dt) dt = new Date();
            var yyyy = dt.getFullYear();
            var MM = this.fixNumber(dt.getMonth() + 1);
            var dd = this.fixNumber(dt.getDate());

            var hh = this.fixNumber(dt.getHours());
            var mm = this.fixNumber(dt.getMinutes());
            var ss = this.fixNumber(dt.getSeconds());

            switch (format) {
                case "yyyy-MM-dd": return `${yyyy}-${MM}-${dd}`;
                case "yyyyMMdd": return `${yyyy}${MM}${dd}`;
            }

            return `${yyyy}-${MM}-${dd} ${hh}:${mm}:${ss}`;
        },
        toFixedTime(dt) {
            if (!dt) dt = new Date();
            var hours = dt.getHours();
            var minutes = this.fixNumber(dt.getMinutes());
            var ampm = hours >= 12 ? "pm" : "am";

            hours = hours % 12;
            hours = hours ? hours : 12;
            return `${hours}:${minutes} ${ampm}`;
        },
        dayOfWeek(dt) {
            var date = new Date(dt);
            switch (date.getDay()) {
                case 0:
                    return "Domingo";
                case 1:
                    return "Lunes";
                case 2:
                    return "Martes";
                case 3:
                    return "Miércoles";
                case 4:
                    return "Jueves";
                case 5:
                    return "Viernes";
                case 6:
                    return "Sábado";
            }
        },
        dayName(date) {
            switch (date.getDay()) {
                case 0:
                    return "Domingo";
                case 1:
                    return "Lunes";
                case 2:
                    return "Martes";
                case 3:
                    return "Miércoles";
                case 4:
                    return "Jueves";
                case 5:
                    return "Viernes";
                case 6:
                    return "Sábado";
            }
        },
        initDayOfWeekDate(day, offset) {
            var today = day || new Date();
            if (offset)
                today.setDate(today.getDate() - (today.getDay() + offset) + 1);
            else
                today.setDate(today.getDate() - today.getDay() + 1);
            return today;
        },
        pad(number, size) {
            var s = String(number);
            while (s.length < (size || 2)) {
                s = "0" + s;
            }
            return s;
        },
        createReceipt(currentSale, receiptImg, myCanvas) {
            var $this = this;
            myCanvas.width = receiptImg.width;
            myCanvas.height = receiptImg.height - 109;
            $this.context = myCanvas.getContext("2d");

            $this.context.clearRect(
                0,
                0,
                myCanvas.width,
                myCanvas.height
            );

            $this.context.fillStyle = "#FFFFFF";
            $this.context.fillRect(
                0,
                0,
                myCanvas.width,
                myCanvas.height
            );
            $this.context.drawImage(
                receiptImg,
                -30,
                -30,
                receiptImg.width,
                receiptImg.height
            );

            // fecha de recibo
            let doneOn = new Date();
            if (currentSale.done_on) {
                try {
                    nextService = new Date(currentSale.done_on)
                } catch (e) {
                    doneOn = new Date();
                }
            }

            $this.context.font = "24px Calibri";
            $this.context.fillStyle = "red";
            $this.context.fillText(
                "REC" + $this.pad(currentSale.id, 5),
                920,
                105
            );
            $this.context.fillText(
                "REC" + $this.pad(currentSale.id, 5),
                920,
                105 + 678
            );

            $this.context.fillStyle = "black";
            $this.context.fillText(
                doneOn.toLocaleDateString("es-ES", {
                    year: "numeric",
                    month: "2-digit",
                    day: "2-digit"
                })
                    .replace("/", "-")
                    .replace("/", "-"),
                920,
                155
            );
            $this.context.fillText(
                doneOn.toLocaleDateString("es-ES", {
                    year: "numeric",
                    month: "2-digit",
                    day: "2-digit"
                })
                    .replace("/", "-")
                    .replace("/", "-"),
                920,
                155 + 678
            );

            // Lineas
            for (var x = 0; x < 8; x++) {
                $this.context.strokeStyle = "black";
                $this.context.beginPath();
                $this.context.moveTo(40, 360 + x * 30);
                $this.context.lineTo(1100, 360 + x * 30);
                $this.context.moveTo(40, 360 + x * 30 + 678);
                $this.context.lineTo(1100, 360 + x * 30 + 678);
                $this.context.lineWidth = 1;
                $this.context.stroke();
            }

            $this.context.font = "16px Calibri";
            // MDP
            if (currentSale.method) {
                var mdp = "Efectivo";
                switch (currentSale.method) {
                    case 2: mdp = `Tarjeta de Crédito`; break;
                    case 3: mdp = `Tarjeta de Débito`; break;
                    case 4: mdp = `Cheque`; break;
                    case 5: mdp = `Transferencia`; break;
                    case 6: mdp = `Crédito`; break;
                }

                $this.context.fillText(mdp, 630, 615);
                $this.context.fillText(mdp, 630, 615 + 678);
            }

            if (currentSale.client) {
                $this.context.fillText(currentSale.client.name, 160, 240);
                $this.context.fillText(currentSale.client.name, 160, 240 + 678);
                $this.context.fillText(
                    currentSale.phonenumber
                        ? currentSale.phonenumber
                        : currentSale.client.phonenumber,
                    160,
                    265
                );
                $this.context.fillText(
                    currentSale.phonenumber
                        ? currentSale.phonenumber
                        : currentSale.client.phonenumber,
                    160,
                    265 + 678
                );
            } else if (currentSale.phonenumber) {
                $this.context.fillText(currentSale.phonenumber, 160, 265);
                $this.context.fillText(currentSale.phonenumber, 160, 265 + 678);
            }

            $this.context.fillText(currentSale.user.name, 160, 290);
            $this.context.fillText(currentSale.user.name, 160, 290 + 678);

            // Tipo: A/C, Ele. Mec.
            $this.context.fillText(currentSale.service_type, 470, 290);
            $this.context.fillText(currentSale.service_type, 470, 290 + 678);

            $this.context.fillText(currentSale.maker, 662, 240);
            $this.context.fillText(currentSale.maker, 662, 240 + 678);
            $this.context.fillText(currentSale.brand, 662, 265);
            $this.context.fillText(currentSale.brand, 662, 265 + 678);
            $this.context.fillText(currentSale.year, 662, 290);
            $this.context.fillText(currentSale.year, 662, 290 + 678);
            $this.context.fillText(currentSale.color, 960, 240);
            $this.context.fillText(currentSale.color, 960, 240 + 678);
            $this.context.fillText(currentSale.km, 960, 265);
            $this.context.fillText(currentSale.km, 960, 265 + 678);
            //$this.context.fillText(currentSale.last_service, 1010, 290);
            //$this.context.fillText(currentSale.last_service, 1010, 290 + 678);

            var nextService = ``;
            if (currentSale.next_service) {
                try {
                    nextService = new Date(currentSale.next_service)
                        .toLocaleDateString("es-ES", {
                            year: "numeric",
                            month: "2-digit",
                            day: "2-digit"
                        })
                        .replace("/", "-")
                        .replace("/", "-")
                } catch (e) {
                    console.error(`No se puedo convertir la fecha de proximo servicio ${currentSale.next_service}`);
                }
            }

            $this.context.fillText(nextService, 1010, 290);
            $this.context.fillText(nextService, 1010, 290 + 678);

            currentSale.total = parseFloat(currentSale.total);

            $this.context.fillText("1", 100, 350);
            $this.context.fillText("1", 100, 350 + 678);
            if (currentSale.concept) {
                var concept = currentSale.concept.match(/.{1,60}/g);
                for (var x = 0; x < concept.length; x++) {
                    $this.context.fillText(concept[x].toUpperCase(), 160, 350 + x * 32);
                    $this.context.fillText(
                        concept[x].toUpperCase(),
                        160,
                        350 + 678 + x * 32
                    );
                }
            }
            $this.context.fillText(
                "$" + $this.formatPrice(currentSale.total),
                950,
                350
            );
            $this.context.fillText(
                "$" + $this.formatPrice(currentSale.total),
                950,
                350 + 678
            );

            // Detalles
            if (currentSale.details) {
                var details = currentSale.details.match(/.{1,60}/g);
                for (var x = 0; x < details.length; x++) {
                    $this.context.fillText(
                        details[x].toUpperCase(),
                        160,
                        350 + 32 * concept.length + x * 32
                    );
                    $this.context.fillText(
                        details[x].toUpperCase(),
                        160,
                        350 + 678 + 32 * concept.length + x * 32
                    );
                }
            }

            $this.context.fillText(
                "$" + $this.formatPrice(currentSale.total),
                950,
                615
            );
            $this.context.fillText(
                "$" + $this.formatPrice(currentSale.total),
                950,
                615 + 678
            );
            if (currentSale.tax) {
                $this.context.fillText(
                    "$" + $this.formatPrice(currentSale.total * 0.08),
                    950,
                    640
                );
                $this.context.fillText(
                    "$" + $this.formatPrice(currentSale.total * 0.08),
                    950,
                    640 + 678
                );
            } else {
                $this.context.fillText("$0", 950, 640);
                $this.context.fillText("$0", 950, 640 + 678);
            }

            if (currentSale.tax) {
                $this.context.fillText(
                    "$" +
                    $this.formatPrice(
                        currentSale.total + currentSale.total * 0.08
                    ),
                    950,
                    668
                );
                $this.context.fillText(
                    "$" +
                    $this.formatPrice(
                        currentSale.total + currentSale.total * 0.08
                    ),
                    950,
                    668 + 678
                );
            } else {
                $this.context.fillText(
                    "$" + $this.formatPrice(currentSale.total),
                    950,
                    668
                );
                $this.context.fillText(
                    "$" + $this.formatPrice(currentSale.total),
                    950,
                    668 + 678
                );
            }

            if (currentSale.guaranty) {
                $this.context.font = "24px Calibri";
                $this.context.fillText(currentSale.guaranty, 170, 620);
                $this.context.fillText(currentSale.guaranty, 170, 620 + 678);
            }

            var img = document.createElement("img");
            img.src = myCanvas.toDataURL("image/jpeg");
            img.width = myCanvas.width;
            img.height = myCanvas.height;

            new Printd().print(img);
        },
        createQuotation(currentSale, quotationImg, myCanvas) {
            var $this = this;

            myCanvas.width = quotationImg.width;
            myCanvas.height = quotationImg.height - 109;
            $this.context = myCanvas.getContext("2d");

            $this.context.clearRect(
                0,
                0,
                myCanvas.width,
                myCanvas.height
            );

            $this.context.fillStyle = "#FFFFFF";
            $this.context.fillRect(
                0,
                0,
                myCanvas.width,
                myCanvas.height
            );
            $this.context.drawImage(
                quotationImg,
                -30,
                -30,
                quotationImg.width,
                quotationImg.height
            );

            $this.context.font = "24px Calibri";
            $this.context.fillStyle = "red";
            $this.context.fillText("COT" + $this.pad(currentSale.id, 5), 935, 132); // FOLIO

            $this.context.fillStyle = "black";
            $this.context.fillText(
                new Date()
                    .toLocaleDateString("es-ES", {
                        year: "numeric",
                        month: "2-digit",
                        day: "2-digit"
                    })
                    .replace("/", "-")
                    .replace("/", "-"),
                930,
                190
            );

            $this.context.font = "16px Calibri";

            // cliente
            if (currentSale.client) {
                $this.context.fillText(currentSale.client.name, 160, 305); // Cliente
                $this.context.fillText(
                    currentSale.phonenumber
                        ? currentSale.phonenumber
                        : currentSale.client.phonenumber,
                    160,
                    330
                );
            } else if (currentSale.phonenumber) {
                $this.context.fillText(currentSale.phonenumber, 160, 330); // tel
            }

            $this.context.fillText(currentSale.user.name, 160, 355); // tecnico

            $this.context.fillText(
                currentSale.maker ? currentSale.maker : currentSale.car[0].maker,
                640,
                305
            );
            $this.context.fillText(
                currentSale.brand ? currentSale.brand : currentSale.car[0].brand,
                640,
                330
            );
            $this.context.fillText(
                currentSale.year ? currentSale.year : currentSale.sale_services[0].year,
                640,
                355
            );
            $this.context.fillText(currentSale.color, 960, 305);
            $this.context.fillText(currentSale.km, 960, 330);

            var nextService = ``;
            if (currentSale.next_service) {
                try {
                    nextService = new Date(currentSale.next_service)
                        .toLocaleDateString("es-ES", {
                            year: "numeric",
                            month: "2-digit",
                            day: "2-digit"
                        })
                        .replace("/", "-")
                        .replace("/", "-")
                } catch (e) {
                    console.error(`No se puedo convertir la fecha de proximo servicio ${currentSale.next_service}`);
                }
            }
            $this.context.fillText(nextService, 980, 355);

            // $this.context.fillText("Negro Mate", 960, 305);
            // $this.context.fillText("125,000", 960, 330);
            // $this.context.fillText("25/04/2021", 980, 355);

            currentSale.total = parseFloat(currentSale.total);

            // Cantidad
            $this.context.fillText("1", 90, 420);

            // Descripcion
            if (currentSale.concept) {
                var concept = currentSale.concept.match(/.{1,60}/g);
                for (var x = 0; x < concept.length; x++) {
                    $this.context.fillText(concept[x].toUpperCase(), 160, 420 + x * 32);
                }
            }

            // Importe
            $this.context.fillText(
                "$" + $this.formatPrice(currentSale.total),
                960,
                420
            );

            // Descripcion (repetida)
            if (currentSale.details) {
                var details = currentSale.details.match(/.{1,60}/g);
                for (var x = 0; x < details.length; x++) {
                    $this.context.fillText(
                        details[x].toUpperCase(),
                        160,
                        420 + 32 * concept.length + x * 32
                    );
                }
            }

            // Lineas
            for (var x = 0; x < 19; x++) {
                $this.context.strokeStyle = "black";
                $this.context.beginPath();
                $this.context.moveTo(40, 430 + x * 31);
                $this.context.lineTo(1100, 430 + x * 31);
                $this.context.lineWidth = 1;
                $this.context.stroke();
            }

            var y = 1040;
            var h = 26;
            // Subtotal
            $this.context.fillText(
                "$" + $this.formatPrice(currentSale.total),
                960,
                y
            );
            y += h;

            // IVA
            if (currentSale.tax) {
                $this.context.fillText(
                    "$" + $this.formatPrice(currentSale.total * 0.08),
                    960,
                    y
                );
            } else {
                $this.context.fillText("$0", 960, y);
            }
            y += h;

            // Total
            if (currentSale.tax) {
                $this.context.fillText(
                    "$" + $this.formatPrice(currentSale.total + currentSale.total * 0.08),
                    960,
                    y
                );
            } else {
                $this.context.fillText(
                    "$" + $this.formatPrice(currentSale.total),
                    960,
                    y
                );
            }
            y += h;

            // Garantia
            if (currentSale.guaranty) {
                //$this.context.font = "24px Calibri";
                $this.context.fillText(currentSale.guaranty, 160, 1040);
            }

            // metodo de pago
            if (currentSale.method) {
                $this.context.fillText(currentSale.method, 620, 1040);
            }

            // validado por...
            if (currentSale.validator) {
                $this.context.fillText(currentSale.validator, 255, 1115);
            }

            // observaciones (56 char por linea)
            if (currentSale.comments) {
                var comment = currentSale.comments.match(/.{1,56}/g);
                for (var i = 0; i < comment.length; i++) {
                    $this.context.fillText(
                        comment[i].toUpperCase().trim(),
                        645,
                        1115 + i * 18
                    );
                }
            }

            var img = document.createElement("img");
            img.src = myCanvas.toDataURL("image/jpeg");
            img.width = myCanvas.width;
            img.height = myCanvas.height;

            new Printd().print(img);
        },
    }
};