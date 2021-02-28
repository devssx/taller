/* You can define here your global methods 
 *
 */
import { Printd } from "printd";

export default {
    methods: {
        fixText(str, max) {
            if (str.length > max)
                return str.substring(0, max) + '...';
            else
                return str;
        },
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
                '#E64D66', '#4DB380', '#FF4D4D', '#99E6E6', '#6666FF',
                '#FF991A', '#CCCC1A', '#AA69B4', '#E6661A', '#5D8000'];
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
        monthName(date) {
            switch (date.getMonth()) {
                case 0: return "Enero";
                case 1: return "Febrero";
                case 2: return "Marzo";
                case 3: return "Abril";
                case 4: return "Mayo";
                case 5: return "Junio";
                case 6: return "Julio";
                case 7: return "Agosto";
                case 8: return "Septiembre";
                case 9: return "Octubre";
                case 10: return "Noviembre";
                case 11: return "Diciembre";
            }
        },
        monthNameByIndex(index) {
            switch (index) {
                case 0: return "Enero";
                case 1: return "Febrero";
                case 2: return "Marzo";
                case 3: return "Abril";
                case 4: return "Mayo";
                case 5: return "Junio";
                case 6: return "Julio";
                case 7: return "Agosto";
                case 8: return "Septiembre";
                case 9: return "Octubre";
                case 10: return "Noviembre";
                case 11: return "Diciembre";
            }
        },
        initDayOfWeekDate(day, offset) {
            var today = new Date(day);
            if (offset)
                today.setDate(today.getDate() - (today.getDay() + offset) + 1);
            else
                today.setDate(today.getDate() - today.getDay() + 1);
            return today;
        },
        endPeriodo(day) {
            var today = new Date(day);
            today.setDate(today.getDate() + 6);
            return today;
        },
        getMonthOfDate(fullDate) {
            return fullDate.toString().substring(4, 6);
        },
        getWeekOfDate(d) {
            // Create a copy of this date object
            var target = new Date(d.valueOf());

            // ISO week date weeks start on monday
            // so correct the day number
            var dayNr = (d.getDay() + 6) % 7;

            // Set the target to the thursday of this week so the
            // target date is in the right year
            target.setDate(target.getDate() - dayNr + 3);

            // ISO 8601 states that week 1 is the week
            // with january 4th in it
            var jan4 = new Date(target.getFullYear(), 0, 4);

            // Number of days between target date and january 4th
            var dayDiff = (target - jan4) / 86400000;

            // Calculate week number: Week 1 (january 4th) plus the
            // number of weeks between target date and january 4th
            var weekNr = 1 + Math.ceil(dayDiff / 7);
            return weekNr;
        },
        pad(number, size) {
            var s = String(number);
            while (s.length < (size || 2)) {
                s = "0" + s;
            }
            return s;
        },
        checkPassword(workshopId, callback) {
            this.$prompt(
                "Ingrese la contraseña continuar.",
                "Alerta: Acción no permitida",
                {
                    confirmButtonText: "OK",
                    cancelButtonText: "Cancel",
                    inputType: "password",
                    inputValue: "",
                }
            ).then(({ value }) => {
                axios.get(`/api/passmanager?name=global&workshop=${workshopId}&password=${value}`).then(function (response) {
                    if (response.data == 1) {
                        callback('success');
                    } else {
                        callback('Contraseña incorrecta.');
                    }
                }).catch((error) => {
                    callback('Error en el Servidor');
                });
            }).catch(() => { });
        },
        confirmar(message, callback) {
            this.$confirm(message, 'Confirmar', {
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                type: 'warning'
            }).then(() => callback()).catch(() => { });
        },
        getWorkShopInfo(id, callback) {
            axios.get(`/api/workshop/info?id=${id}`).then((response) => callback(response.data)).catch((error) => {
                // Error
            });
        },
        createReceipt(currentSale, receiptImg, myCanvas, format){
            const $this = this;
            let id = currentSale.workshop_id;
            $this.getWorkShopInfo(id, (data) => $this.createReceiptEx(currentSale, receiptImg, myCanvas, format, data));
        },
        createReceiptEx(currentSale, receiptImg, myCanvas, format, workshopInfo) {
            var $this = this;
            myCanvas.width = receiptImg.width;
            myCanvas.height = receiptImg.height - 109;
            $this.context = myCanvas.getContext("2d");
            $this.context.clearRect(0, 0, myCanvas.width, myCanvas.height);

            $this.context.fillStyle = "#FFFFFF";
            $this.context.fillRect(0, 0, myCanvas.width, myCanvas.height);
            $this.context.drawImage(receiptImg, -30, -30, receiptImg.width, receiptImg.height);

            // Header Direccion del taller
            $this.context.font = "20px Calibri";
            $this.context.fillStyle = "black";
            $this.wrapText($this.context, workshopInfo.address, 275, 80, 580, 22);
            $this.wrapText($this.context, workshopInfo.address, 275, 80 + 678, 580, 22);
            $this.context.textAlign = "center";
            $this.context.fillText(workshopInfo.phone, 555, 130);
            $this.context.fillText(workshopInfo.account, 555, 154);
            $this.context.fillText(workshopInfo.phone, 555, 130 + 678);
            $this.context.fillText(workshopInfo.account, 555, 154 + 678);

            $this.context.font = "12px Calibri";
            $this.context.fillText(`Sucursal:`, 320, 172);
            $this.context.fillText(`Sucursal:`, 850, 172);
            $this.context.fillText(workshopInfo.sucursal1, 320, 185);
            $this.context.fillText(workshopInfo.sucursal2, 850, 185);
            $this.context.fillText(`Sucursal:`, 320, 172 + 678);
            $this.context.fillText(`Sucursal:`, 850, 172 + 678);
            $this.context.fillText(workshopInfo.sucursal1, 320, 185 + 678);
            $this.context.fillText(workshopInfo.sucursal2, 850, 185 + 678);

            // fecha de recibo
            $this.context.textAlign = "left";
            $this.context.font = "24px Calibri";
            let doneOn = new Date();
            if (currentSale.done_on) {
                try {
                    doneOn = new Date(currentSale.done_on)
                } catch (e) {
                    doneOn = new Date();
                }
            }

            // FOLIO
            $this.context.font = "24px Calibri";
            $this.context.fillStyle = "red";
            $this.context.fillText("REC" + $this.pad(currentSale.id, 5), 920, 105);
            $this.context.fillText("REC" + $this.pad(currentSale.id, 5), 920, 105 + 678);

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

            var nextService = ``;
            if (currentSale.next_service && currentSale.next_service != `0000-00-00`) {
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

            // No cabe en el recibo solo hay 9 renglones
            if (format > 0 && currentSale.sale_services.length > 7) {
                format = 0;
            }

            var y = 0;
            currentSale.total = parseFloat(currentSale.total);

            // Cantidad
            $this.context.fillText("1", 100, 350);
            $this.context.fillText("1", 100, 350 + 678);

            // Concepto
            if (format == 0 && currentSale.concept) {
                var concept = currentSale.concept.match(/.{1,90}/g);
                for (var x = 0; x < concept.length; x++) {
                    // formato: 1. Nombre = 1500
                    // var patt = new RegExp("^\\d+\\..+=\\D+\\d+");
                    // if (patt.test(concept[x])) {
                    // } else {                     
                    // }

                    $this.context.fillText(concept[x].toUpperCase(), 160, 350 + y * 30);
                    $this.context.fillText(concept[x].toUpperCase(), 160, 350 + 678 + y * 30);
                    y++;
                }
            }

            // Importe Total (Cuando no se detalla el listado de items)
            if (format == 0 || format == 1) {
                $this.context.textAlign = "end";
                $this.context.fillText("$" + $this.formatPrice(currentSale.total), 1080, 350);
                $this.context.fillText("$" + $this.formatPrice(currentSale.total), 1080, 350 + 678);
                $this.context.textAlign = "start";
            }

            // Descripcion de items
            if (format > 0) {
                for (var x = 0; x < currentSale.sale_services.length; x++) {
                    var desc = currentSale.sale_services[x];
                    // Cantidad
                    $this.context.fillText("1", 100, 350 + y * 31);
                    $this.context.fillText("1", 100, 350 + 678 + y * 31);

                    // Item Name
                    $this.context.fillText(desc.item.name.toUpperCase(), 160, 350 + y * 30);
                    $this.context.fillText(desc.item.name.toUpperCase(), 160, 350 + 678 + y * 30);

                    // Importe
                    if (format == 2) {
                        $this.context.textAlign = "end";
                        $this.context.fillText("$" + $this.formatPrice(desc.price), 1080, 350 + y * 30);
                        $this.context.fillText("$" + $this.formatPrice(desc.price), 1080, 350 + 678 + y * 30);
                        $this.context.textAlign = "start";
                    }
                    y++;
                }
            }

            // Detalles
            if (currentSale.details && y <= 6) {
                y = 6;
                $this.wrapText($this.context, `Observación: ${currentSale.details}`, 160, 350 + y * 30, 780, 30);
                $this.wrapText($this.context, `Observación: ${currentSale.details}`, 160, 350 + y * 30 + 678, 780, 30);
            }

            $this.context.textAlign = "end";
            $this.context.fillText("$" + $this.formatPrice(currentSale.total), 1080, 615);
            $this.context.fillText("$" + $this.formatPrice(currentSale.total), 1080, 615 + 678);
            if (currentSale.tax == 1) {
                $this.context.fillText("$" + $this.formatPrice(currentSale.total * 0.08), 1080, 640);
                $this.context.fillText("$" + $this.formatPrice(currentSale.total * 0.08), 1080, 640 + 678);
            } else {
                $this.context.fillText("$0.00", 1080, 640);
                $this.context.fillText("$0.00", 1080, 640 + 678);
            }

            if (currentSale.tax == 1) {
                $this.context.fillText("$" + $this.formatPrice(currentSale.total + currentSale.total * 0.08), 1080, 668);
                $this.context.fillText("$" + $this.formatPrice(currentSale.total + currentSale.total * 0.08), 1080, 668 + 678);
            } else {
                $this.context.fillText("$" + $this.formatPrice(currentSale.total), 1080, 668);
                $this.context.fillText("$" + $this.formatPrice(currentSale.total), 1080, 668 + 678);
            }

            // Garantia (Max 165 chars)
            if (currentSale.guaranty) {
                $this.context.textAlign = "start";
                $this.context.font = "12px Calibri";
                $this.wrapText($this.context, currentSale.guaranty, 157, 612, 310, 12);
                $this.wrapText($this.context, currentSale.guaranty, 157, 612 + 678, 310, 12);
            }

            var img = document.createElement("img");
            img.src = myCanvas.toDataURL("image/jpeg");
            img.width = myCanvas.width;
            img.height = myCanvas.height;

            new Printd().print(img);
        },
        createQuotation(currentSale, quotationImg, myCanvas, format){
            const $this = this;
            let id = currentSale.workshop_id;
            $this.getWorkShopInfo(id, (data) => $this.createQuotationEx(currentSale, quotationImg, myCanvas, format, data));
        },
        createQuotationEx(currentSale, quotationImg, myCanvas, format, workshopInfo) {
            var $this = this;

            myCanvas.width = quotationImg.width;
            myCanvas.height = quotationImg.height - 109;
            $this.context = myCanvas.getContext("2d");

            $this.context.clearRect(0, 0, myCanvas.width, myCanvas.height);

            $this.context.fillStyle = "#FFFFFF";
            $this.context.fillRect(0, 0, myCanvas.width, myCanvas.height);
            $this.context.drawImage(quotationImg, -30, -30, quotationImg.width, quotationImg.height);

            // Header Direccion del taller
            $this.context.font = "20px Calibri";
            $this.context.fillStyle = "black";
            $this.wrapText($this.context, workshopInfo.address, 225, 140, 650, 22);
            $this.context.textAlign = "center";
            $this.context.fillText(workshopInfo.account, 550, 190);
            $this.context.fillText(workshopInfo.phone, 550, 214);

            $this.context.font = "12px Calibri";
            $this.context.fillText(`Sucursal:`, 320, 232);
            $this.context.fillText(`Sucursal:`, 850, 232);
            $this.context.fillText(workshopInfo.sucursal1, 320, 245);
            $this.context.fillText(workshopInfo.sucursal2, 850, 245);

            $this.context.textAlign = "left";
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

            $this.context.fillStyle = "black";
            $this.context.font = "16px Calibri";

            // cliente
            if (currentSale.client) {
                $this.context.fillText(currentSale.client.name, 160, 305); // Cliente
                $this.context.fillText(currentSale.phonenumber ? currentSale.phonenumber : currentSale.client.phonenumber, 160, 330);
            } else if (currentSale.phonenumber) {
                $this.context.fillText(currentSale.phonenumber, 160, 330); // tel
            }

            $this.context.fillText(currentSale.user.name, 160, 355); // tecnico
            $this.context.fillText(currentSale.maker ? currentSale.maker : currentSale.car[0].maker, 640, 305);
            $this.context.fillText(currentSale.brand ? currentSale.brand : currentSale.car[0].brand, 640, 330);
            $this.context.fillText(currentSale.year ? currentSale.year : currentSale.sale_services[0].year, 640, 355);
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
            var y = 0;
            currentSale.total = parseFloat(currentSale.total);

            // Cantidad
            $this.context.fillText("1", 90, 420);

            // Descripcion
            if (format == 0 && currentSale.concept) {
                var concept = currentSale.concept.match(/.{1,75}/g);
                for (var x = 0; x < concept.length; x++) {
                    $this.context.fillText(concept[x].toUpperCase(), 160, 420 + y * 31);
                    y++;
                }
            }

            if (format == 0 || format == 1) {
                // Importe
                $this.context.textAlign = "end";
                $this.context.fillText("$" + $this.formatPrice(currentSale.total), 1080, 420);
                $this.context.textAlign = "start";
            }

            // Descripcion
            if (format > 0) {
                for (var x = 0; x < currentSale.sale_services.length; x++) {
                    var desc = currentSale.sale_services[x];
                    // Cantidad
                    $this.context.fillText("1", 90, 420 + y * 31);
                    // Item Name
                    $this.context.fillText(desc.item.name.toUpperCase(), 160, 420 + y * 31);
                    // Importe
                    if (format == 2) {
                        $this.context.textAlign = "end";
                        $this.context.fillText("$" + $this.formatPrice(desc.price), 1080, 420 + y * 31);
                        $this.context.textAlign = "start";
                    }
                    y++;
                }
            }

            // Detalles
            if (currentSale.details && y <= 17) {
                y = 17;
                $this.wrapText($this.context, `Observación: ${currentSale.details}`, 160, 420 + y * 31, 780, 31);
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
            $this.context.textAlign = "end";
            $this.context.fillText("$" + $this.formatPrice(currentSale.total), 1080, y);
            y += h;

            // IVA
            if (currentSale.tax == 1) {
                $this.context.fillText("$" + $this.formatPrice(currentSale.total * 0.08), 1080, y);
            } else {
                $this.context.fillText("$0.00", 1080, y);
            }
            y += h;

            // Total
            if (currentSale.tax == 1) {
                $this.context.fillText("$" + $this.formatPrice(currentSale.total + currentSale.total * 0.08), 1080, y);
            } else {
                $this.context.fillText("$" + $this.formatPrice(currentSale.total), 1080, y);
            }
            y += h;

            // metodo de pago
            $this.context.textAlign = "start";
            if (currentSale.method && currentSale.method != 0) {
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
                    $this.context.fillText(comment[i].toUpperCase().trim(), 645, 1115 + i * 18);
                }
            }

            // Garantia
            if (currentSale.guaranty) {
                $this.context.font = "12px Calibri";
                $this.wrapText($this.context, currentSale.guaranty, 157, 1035, 285, 12);
            }

            var img = document.createElement("img");
            img.src = myCanvas.toDataURL("image/jpeg");
            img.width = myCanvas.width;
            img.height = myCanvas.height;

            new Printd().print(img);
        },
        wrapText(context, text, x, y, maxWidth, lineHeight) {
            var lines = text.split("\n");
            for (var i = 0; i < lines.length; i++) {
                var words = lines[i].split(' ');
                var line = '';

                for (var n = 0; n < words.length; n++) {
                    var testLine = line + words[n] + ' ';
                    var metrics = context.measureText(testLine);
                    var testWidth = metrics.width;
                    if (testWidth > maxWidth && n > 0) {
                        context.fillText(line, x, y);
                        line = words[n] + ' ';
                        y += lineHeight;
                    } else {
                        line = testLine;
                    }
                }

                context.fillText(line, x, y);
                y += lineHeight;
            }
        }
    }
};