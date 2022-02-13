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
        loadMyWorkShop(callback) {
            axios.get("/app").then(function (response) {
                callback(response.data[0].workshop_id);
            });
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
        createReceipt(currentSale, receiptImg, myCanvas, format) {
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
            $this.context.drawImage(receiptImg, -25, -25, receiptImg.width, receiptImg.height);

            // Header Direccion del taller
            $this.context.textAlign = "center";
            $this.context.font = "bold 20px Calibri";
            $this.context.fillStyle = "black";
            $this.context.fillText(workshopInfo.address, 550, 55);
            $this.context.fillText(workshopInfo.address, 550, 55 + 785);
            $this.context.font = "20px Calibri";
            $this.context.fillText(workshopInfo.phone, 550, 75 + 785);

            $this.context.font = "16px Calibri";
            $this.wrapText($this.context, workshopInfo.sucursal1, 250, 132, 500, 16);
            $this.wrapText($this.context, workshopInfo.sucursal2, 800, 132, 400, 16);


            $this.context.textAlign = "left";
            $this.context.font = "18px Calibri";
            $this.context.fillStyle = "red";
            $this.context.fillText($this.pad(currentSale.folio, 5), 1015, 50); // FOLIO
            $this.context.fillText($this.pad(currentSale.folio, 5), 1000, 50 + 787); // FOLIO

            // fecha de recibo
            $this.context.fillStyle = "black";
            let doneOn = new Date();
            if (currentSale.done_on) {
                try {
                    doneOn = new Date(currentSale.done_on)
                } catch (e) {
                    doneOn = new Date();
                }
            }

            $this.context.fillStyle = "black";
            $this.context.fillText(
                doneOn.toLocaleDateString("es-ES", {
                    year: "numeric",
                    month: "2-digit",
                    day: "2-digit"
                })
                    .replace("/", "-")
                    .replace("/", "-"),
                995,
                70
            );

            $this.context.fillText(
                doneOn.toLocaleDateString("es-ES", {
                    year: "numeric",
                    month: "2-digit",
                    day: "2-digit"
                })
                    .replace("/", "-")
                    .replace("/", "-"),
                980,
                70 + 787
            );


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
                $this.context.textAlign = "center";
                $this.context.fillText(mdp, 720, 570);
                $this.context.fillText(mdp.replace('Transferencia', 'Transf').replace('Tarjeta de', 'T.'), 800, 570 + 680);
                $this.context.textAlign = "left";
            }


            // cliente
            if (currentSale.client) {
                $this.context.fillText(currentSale.client.name, 100, 197); // Cliente
                $this.context.fillText(currentSale.client.name, 100, 197 + 698); // Cliente
                $this.context.fillText(currentSale.phonenumber ? currentSale.phonenumber : currentSale.client.phonenumber, 100, 218);
                $this.context.fillText(currentSale.phonenumber ? currentSale.phonenumber : currentSale.client.phonenumber, 100, 218 + 695);
                $this.context.fillText(currentSale.client.id, 440, 219);
                $this.context.fillText(currentSale.client.id, 500, 219 + 693);
            } else if (currentSale.phonenumber) {
                $this.context.fillText(currentSale.phonenumber, 100, 219 + 695); // tel
            }

            $this.context.fillText(currentSale.user.name, 100, 240); // tecnico
            $this.context.fillText(currentSale.user.name, 100, 240 + 690); // tecnico
            $this.context.fillText(currentSale.maker ? currentSale.maker : currentSale.car[0].maker, 600, 197);
            $this.context.fillText(currentSale.maker ? currentSale.maker : currentSale.car[0].maker, 680, 197 + 697);
            $this.context.fillText(currentSale.brand ? currentSale.brand : currentSale.car[0].brand, 600, 219);
            $this.context.fillText(currentSale.brand ? currentSale.brand : currentSale.car[0].brand, 680, 219 + 693);
            $this.context.fillText(currentSale.year ? currentSale.year : currentSale.sale_services[0].year, 600, 240);
            $this.context.fillText(currentSale.year ? currentSale.year : currentSale.sale_services[0].year, 680, 240 + 690);
            $this.context.fillText(currentSale.color, 925, 197);
            $this.context.fillText(currentSale.color, 960, 197 + 695);
            $this.context.fillText(currentSale.km, 925, 219);
            $this.context.fillText(currentSale.km, 960, 219 + 693);

            // Tipo: A/C, Ele. Mec.
            // tipo de servicio
            if (currentSale.service_type == 1) {
                $this.context.fillText('A/C', 440, 240);
                $this.context.fillText('A/C', 500, 240 + 690);
            }
            else if (currentSale.service_type == 2) {
                $this.context.fillText('Mecánico', 440, 240);
                $this.context.fillText('Mecánico', 500, 240 + 690);
            }
            else if (currentSale.service_type == 3) {
                $this.context.fillText('Eléctrico', 440, 240);
                $this.context.fillText('Eléctrico', 500, 240 + 690);
            }

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

            $this.context.fillText(nextService, 925, 240);
            $this.context.fillText(nextService, 960, 240 + 690);

            // Conceptos
            $this.context.font = "12px Calibri";

            // No cabe en el recibo solo hay 13 renglones
            if (format > 0 && currentSale.sale_services.length > 14) {
                alert("La cantidad de artículos excede el número de renglones");
            }

            var y = 0.0;
            currentSale.total = parseFloat(currentSale.total);


            // Cantidad
            $this.context.fillText("1", 85, 285);
            $this.context.fillText("1", 85, 285 + 684);

            // Descripcion
            if (format == 0) {
                if (currentSale.concept) {
                    $this.wrapText($this.context, currentSale.concept.toUpperCase(), 170, 285, 750, 19.2);
                    $this.wrapText($this.context, currentSale.concept.toUpperCase(), 170, 285 + 684, 750, 19.2);
                } else {
                    alert("No hay información en el campo concepto, favor de editarlo si se va a imprimir en este formato");
                }
            }

            if (format == 0 || format == 1) {
                // Importe
                $this.context.textAlign = "end";
                $this.context.fillText("$" + $this.formatPrice(currentSale.total), 1080, 285);
                $this.context.fillText("$" + $this.formatPrice(currentSale.total), 1060, 285 + 684);
                $this.context.textAlign = "start";
            }

            // Descripcion
            if (format > 0) {
                for (var x = 0; x < currentSale.sale_services.length; x++) {
                    var desc = currentSale.sale_services[x];
                    // Cantidad
                    $this.context.fillText("1", 85, 285 + y * 19.2);
                    $this.context.fillText("1", 85, 285 + 684 + y * 19.2);
                    // Item Name
                    $this.context.fillText(desc.item.name.toUpperCase(), 170, 285 + y * 19.2);
                    $this.context.fillText(desc.item.name.toUpperCase(), 170, 285 + 684 + y * 19.2);
                    // Importe
                    if (format == 2) {
                        $this.context.textAlign = "end";
                        $this.context.fillText("$" + $this.formatPrice(desc.price), 1080, 285 + y * 19.2);
                        $this.context.fillText("$" + $this.formatPrice(desc.price), 1060, 285 + 684 + y * 19.2);
                        $this.context.textAlign = "start";
                    }
                    y++;
                }
            }

            // Detalles
            if (currentSale.details && y <= 11) {
                y = 11;
                $this.wrapText($this.context, `Observación: ${currentSale.details}`.toUpperCase(), 170, 285 + y * 19.2, 750, 19.2);
                $this.wrapText($this.context, `Observación: ${currentSale.details}`.toUpperCase(), 170, 285 + 684 + y * 19.2, 750, 19.2);
            }

            // Subtotal
            $this.context.textAlign = "end";
            $this.context.fillText("$" + $this.formatPrice(currentSale.total), 1080, 557);
            $this.context.fillText("$" + $this.formatPrice(currentSale.total), 1060, 557 + 682);

            // IVA
            if (currentSale.tax == 1) {
                $this.context.fillText("$" + $this.formatPrice(currentSale.total * 0.08), 1080, 580);
                $this.context.fillText("$" + $this.formatPrice(currentSale.total * 0.08), 1060, 580 + 678);
            } else {
                $this.context.fillText("$0.00", 1080, 580);
                $this.context.fillText("$0.00", 1060, 580 + 678);
            }

            // Total
            if (currentSale.tax == 1) {
                $this.context.fillText("$" + $this.formatPrice(currentSale.total + currentSale.total * 0.08), 1080, 605);
                $this.context.fillText("$" + $this.formatPrice(currentSale.total + currentSale.total * 0.08), 1060, 600 + 682);
            } else {
                $this.context.fillText("$" + $this.formatPrice(currentSale.total), 1080, 605);
                $this.context.fillText("$" + $this.formatPrice(currentSale.total), 1060, 600 + 682);
            }

            // Garantia (Max 165 chars)
            if (currentSale.guaranty) {
                $this.context.textAlign = "start";
                $this.context.font = "12px Calibri";
                $this.wrapText($this.context, currentSale.guaranty, 170, 555, 330, 12);
                $this.wrapText($this.context, currentSale.guaranty, 170, 555 + 684, 330, 12);
            }

            // observaciones
            if (currentSale.comments) {
                $this.context.font = "12px Calibri";
                $this.wrapText($this.context, currentSale.comments, 170, 630, 330, 12);
                $this.wrapText($this.context, currentSale.comments, 170, 630 + 673, 330, 12);
            }

            //PIEZAS
            let parts = currentSale.parts;
            //$this.context.fillStyle = "red";
            $this.context.font = "30px Calibri";
            $this.drawCheck($this.context, 40, 687, parts.includes('A'));
            $this.drawCheck($this.context, 98, 687, parts.includes('B'));
            $this.drawCheck($this.context, 160, 687, parts.includes('C'));
            $this.drawCheck($this.context, 225, 687, parts.includes('D'));
            $this.drawCheck($this.context, 300, 687, parts.includes('E'));
            $this.drawCheck($this.context, 360, 687, parts.includes('F'));
            $this.drawCheck($this.context, 418, 687, parts.includes('G'));
            $this.drawCheck($this.context, 490, 687, parts.includes('H'));
            $this.drawCheck($this.context, 565, 687, parts.includes('I'));
            $this.drawCheck($this.context, 625, 687, parts.includes('J'));
            $this.drawCheck($this.context, 730, 687, parts.includes('K'));
            $this.drawCheck($this.context, 820, 687, parts.includes('L'));
            $this.drawCheck($this.context, 902, 687, parts.includes('M'));
            $this.drawCheck($this.context, 980, 687, parts.includes('N'));
            $this.drawCheck($this.context, 1050, 687, parts.includes('O'));

            var img = document.createElement("img");
            img.src = myCanvas.toDataURL("image/jpeg");
            img.width = myCanvas.width;
            img.height = myCanvas.height;

            new Printd().print(img);
        },
        createQuotation(currentSale, quotationImg, myCanvas, format) {
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
            $this.context.drawImage(quotationImg, -25, -25, quotationImg.width, quotationImg.height);

            // Header Direccion del taller
            $this.context.textAlign = "center";
            $this.context.font = "bold 20px Calibri";
            $this.context.fillStyle = "black";
            $this.context.fillText(workshopInfo.address, 550, 55);
            $this.context.font = "20px Calibri";
            $this.context.fillText(workshopInfo.phone, 550, 75);

            $this.context.font = "16px Calibri";
            $this.wrapText($this.context, workshopInfo.sucursal1, 250, 132, 500, 16);
            $this.wrapText($this.context, workshopInfo.sucursal2, 800, 132, 400, 16);

            $this.context.textAlign = "left";
            $this.context.font = "18px Calibri";
            $this.context.fillStyle = "red";
            $this.context.fillText($this.pad(currentSale.folio, 5), 1020, 50); // FOLIO

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
                1000,
                70
            );

            $this.context.font = "16px Calibri";

            // cliente
            if (currentSale.client) {
                $this.context.fillText(currentSale.client.name, 100, 197); // Cliente
                $this.context.fillText(currentSale.phonenumber ? currentSale.phonenumber : currentSale.client.phonenumber, 100, 218);
                $this.context.fillText(currentSale.client.id, 380, 219);
            } else if (currentSale.phonenumber) {
                $this.context.fillText(currentSale.phonenumber, 100, 219); // tel
            }

            // tipo de servicio
            if (currentSale.service_type == 1)
                $this.context.fillText('A/C', 380, 240);
            else if (currentSale.service_type == 2)
                $this.context.fillText('Mecánico', 380, 240);
            else if (currentSale.service_type == 3)
                $this.context.fillText('Eléctrico', 380, 240);

            $this.context.fillText(currentSale.user.name, 100, 240); // tecnico
            $this.context.fillText(currentSale.maker ? currentSale.maker : currentSale.car[0].maker, 600, 197);
            $this.context.fillText(currentSale.brand ? currentSale.brand : currentSale.car[0].brand, 600, 219);
            $this.context.fillText(currentSale.year ? currentSale.year : currentSale.sale_services[0].year, 600, 240);
            $this.context.fillText(currentSale.color, 925, 197);
            $this.context.fillText(currentSale.km, 925, 219);

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
            $this.context.fillText(nextService, 925, 242);
            var y = 0.0;
            currentSale.total = parseFloat(currentSale.total);

            // Cantidad
            $this.context.fillText("1", 95, 295);

            // Descripcion
            if (format == 0 && currentSale.concept) {
                $this.wrapText($this.context, currentSale.concept.toUpperCase(), 200, 295, 730, 28.4);
            }

            if (format == 0 || format == 1) {
                // Importe
                $this.context.textAlign = "end";
                $this.context.fillText("$" + $this.formatPrice(currentSale.total), 1090, 295);
                $this.context.textAlign = "start";
            }

            // Descripcion
            if (format > 0) {
                for (var x = 0; x < currentSale.sale_services.length; x++) {
                    var desc = currentSale.sale_services[x];
                    // Cantidad
                    $this.context.fillText("1", 95, 295 + y * 28.4);
                    // Item Name
                    $this.context.fillText(desc.item.name.toUpperCase(), 200, 295 + y * 28.4);
                    // Importe
                    if (format == 2) {
                        $this.context.textAlign = "end";
                        $this.context.fillText("$" + $this.formatPrice(desc.price), 1090, 295 + y * 28.4);
                        $this.context.textAlign = "start";
                    }
                    y++;
                }
            }

            // Detalles
            if (currentSale.details && y <= 11) {
                y = 11;
                $this.wrapText($this.context, `Observación: ${currentSale.details}`.toUpperCase(), 200, 295 + y * 28.4, 730, 28.4);
            }

            // Subtotal
            $this.context.textAlign = "end";
            $this.context.fillText("$" + $this.formatPrice(currentSale.total), 1090, 693);

            // IVA
            if (currentSale.tax == 1) {
                $this.context.fillText("$" + $this.formatPrice(currentSale.total * 0.08), 1090, 722);
            } else {
                $this.context.fillText("$0.00", 1090, 722);
            }

            // Total
            if (currentSale.tax == 1) {
                $this.context.fillText("$" + $this.formatPrice(currentSale.total + currentSale.total * 0.08), 1090, 757);
            } else {
                $this.context.fillText("$" + $this.formatPrice(currentSale.total), 1090, 757);
            }

            // Garantia
            $this.context.textAlign = "start";
            if (currentSale.guaranty) {
                $this.context.font = "12px Calibri";
                $this.wrapText($this.context, currentSale.guaranty, 280, 685, 520, 12);
            }

            // observaciones
            if (currentSale.comments) {
                $this.context.font = "12px Calibri";
                $this.wrapText($this.context, currentSale.comments, 280, 743, 520, 12);
            }

            // cuenta transferencia
            $this.context.font = "16px Calibri";
            $this.context.fillText(workshopInfo.account, 285, 790);

            // validado por...
            if (currentSale.validator) {
                $this.context.fillText(currentSale.validator, 285, 850);
            }

            //PIEZAS
            //$this.context.fillStyle = "red";
            $this.context.font = "30px Calibri";
            $this.drawCheck($this.context, 40, 882, currentSale.parts.includes('A'));
            $this.drawCheck($this.context, 98, 882, currentSale.parts.includes('B'));
            $this.drawCheck($this.context, 160, 882, currentSale.parts.includes('C'));
            $this.drawCheck($this.context, 225, 882, currentSale.parts.includes('D'));
            $this.drawCheck($this.context, 300, 882, currentSale.parts.includes('E'));
            $this.drawCheck($this.context, 360, 882, currentSale.parts.includes('F'));
            $this.drawCheck($this.context, 418, 882, currentSale.parts.includes('G'));
            $this.drawCheck($this.context, 490, 882, currentSale.parts.includes('H'));
            $this.drawCheck($this.context, 565, 882, currentSale.parts.includes('I'));
            $this.drawCheck($this.context, 625, 882, currentSale.parts.includes('J'));
            $this.drawCheck($this.context, 730, 882, currentSale.parts.includes('K'));
            $this.drawCheck($this.context, 820, 882, currentSale.parts.includes('L'));
            $this.drawCheck($this.context, 902, 882, currentSale.parts.includes('M'));
            $this.drawCheck($this.context, 980, 882, currentSale.parts.includes('N'));
            $this.drawCheck($this.context, 1050, 882, currentSale.parts.includes('O'));


            var img = document.createElement("img");
            img.src = myCanvas.toDataURL("image/jpeg");
            img.width = myCanvas.width;
            img.height = myCanvas.height;

            new Printd().print(img);
        },
        drawCheck(context, x, y, checked) {
            if (checked)
                context.fillText('x', x, y);

            context.beginPath();
            context.rect(x - 1, y - 15, 16, 16);
            context.stroke();
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
        },
        getDefaultCarServices(car) {
            let servicioAC = {
                car: car,
                service: 22,
                items: [
                    {
                        name: "Servicio de A/C",
                        id: 20,
                        price: 750,
                        low_price: 750,
                        mid_price: 750,
                        high_price: 750,
                    },
                    {
                        name: "Valvulas de Servicio",
                        id: 43,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    },
                    {
                        name: "Filtro de Cabina",
                        id: 13,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    }
                ]
            };

            let afinacion = {
                car: car,
                service: 29,
                items: [
                    {
                        name: "Aceite",
                        id: 2,
                    },
                    {
                        name: "Filtro de Aceite",
                        id: 36,
                    },
                    {
                        name: "Filtro de Aire",
                        id: 38,
                    },
                    {
                        name: "Limpieza de Inyectores",
                        id: 39,
                    },
                    {
                        name: "Relleno de Niveles",
                        id: 40,
                    },
                    {
                        name: "Diagnostico por Computadora",
                        id: 41,
                    },
                    {
                        name: "Mano de Obra",
                        id: 1,
                    }
                ]
            };

            let cambioBobina = {
                car: car,
                service: 30,
                items: [
                    {
                        name: "Empacado de Compresor",
                        id: 35,
                    },
                    {
                        name: "Mano de Obra",
                        id: 1,
                    },
                    {
                        name: "Servicio de A/C",
                        id: 20,
                        price: 750,
                        low_price: 750,
                        mid_price: 750,
                        high_price: 750,
                    },
                    {
                        name: "Valvulas de Servicio",
                        id: 43,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    },
                    {
                        name: "Filtro de Cabina",
                        id: 13,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    }
                ]
            };

            let reemplazoBlower = {
                car: car,
                service: 31,
                items: [
                    {
                        name: "Resistencia",
                        id: 27,
                    },
                    {
                        name: "Blower",
                        id: 37,
                    },
                    {
                        name: "Mano de Obra",
                        id: 1,
                        price: 350,
                        low_price: 350,
                        mid_price: 350,
                        high_price: 350,
                    }
                ]
            };

            let cambioAceite = {
                car: car,
                service: 1,
                items: [
                    {
                        name: "Aceite",
                        id: 2,
                    },
                    {
                        name: "Filtro de Aceite",
                        id: 36,
                    },
                    {
                        name: "Mano de Obra",
                        id: 1,
                        price: 100,
                        low_price: 100,
                        mid_price: 100,
                        high_price: 100,
                    }
                ]
            };

            let empacadoCompresor = {
                car: car,
                service: 32,
                items: [
                    {
                        name: "Empacado de Compresor",
                        id: 35,
                    },
                    {
                        name: "Mano de Obra",
                        id: 1,
                    },
                    {
                        name: "Servicio de A/C",
                        id: 20,
                        price: 750,
                        low_price: 750,
                        mid_price: 750,
                        high_price: 750,
                    },
                    {
                        name: "Valvulas de Servicio",
                        id: 43,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    },
                    {
                        name: "Filtro de Cabina",
                        id: 13,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    }
                ]
            };

            let reemplazoBaleroCompresor = {
                car: car,
                service: 33,
                items: [
                    {
                        name: "Balero de Compresor",
                        id: 34,
                    },
                    {
                        name: "Empaques a Tomas de Compresor",
                        id: 29,
                    },
                    {
                        name: "Mano de Obra",
                        id: 1,
                    },
                    {
                        name: "Servicio de A/C",
                        id: 20,
                        price: 750,
                        low_price: 750,
                        mid_price: 750,
                        high_price: 750,
                    },
                    {
                        name: "Valvulas de Servicio",
                        id: 43,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    },
                    {
                        name: "Filtro de Cabina",
                        id: 13,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    }
                ]
            };

            let reemplazoClutchCompresor = {
                car: car,
                service: 34,
                items: [
                    {
                        name: "Clutch de Compresor",
                        id: 33,
                    },
                    {
                        name: "Empaques a Tomas de Compresor",
                        id: 29,
                    },
                    {
                        name: "Mano de Obra",
                        id: 1,
                    },
                    {
                        name: "Servicio de A/C",
                        id: 20,
                        price: 750,
                        low_price: 750,
                        mid_price: 750,
                        high_price: 750,
                    },
                    {
                        name: "Valvulas de Servicio",
                        id: 43,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    },
                    {
                        name: "Filtro de Cabina",
                        id: 13,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    }
                ]
            };

            let reemplazoManguera = {
                car: car,
                service: 5,
                items: [
                    {
                        name: "Manguera",
                        id: 24,
                    },
                    {
                        name: "Empaques a Tomas de Condensador",
                        id: 32,
                    },
                    {
                        name: "Mano de Obra",
                        id: 1,
                    },
                    {
                        name: "Servicio de A/C",
                        id: 20,
                        price: 750,
                        low_price: 750,
                        mid_price: 750,
                        high_price: 750,
                    },
                    {
                        name: "Valvulas de Servicio",
                        id: 43,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    },
                    {
                        name: "Filtro de Cabina",
                        id: 13,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    }
                ]
            };

            let reemplazoCondensador = {
                car: car,
                service: 3,
                items: [
                    {
                        name: "Condensador",
                        id: 7,
                    },
                    {
                        name: "Mano de Obra",
                        id: 1,
                    },
                    {
                        name: "Servicio de A/C",
                        id: 20,
                        price: 750,
                        low_price: 750,
                        mid_price: 750,
                        high_price: 750,
                    },
                    {
                        name: "Valvulas de Servicio",
                        id: 43,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    },
                    {
                        name: "Filtro de Cabina",
                        id: 13,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    }
                ]
            };

            let reemplazoEvaporador = {
                car: car,
                service: 4,
                items: [
                    {
                        name: "Evaporador",
                        id: 11,
                    },
                    {
                        name: "Empaques a Tomas de Evaporador",
                        id: 31,
                    },
                    {
                        name: "Mano de Obra",
                        id: 1,
                    },
                    {
                        name: "Servicio de A/C",
                        id: 20,
                        price: 750,
                        low_price: 750,
                        mid_price: 750,
                        high_price: 750,
                    },
                    {
                        name: "Valvulas de Servicio",
                        id: 43,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    },
                    {
                        name: "Filtro de Cabina",
                        id: 13,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    }
                ]
            };

            let reemplazoCompresor = {
                car: car,
                service: 2,
                items: [
                    {
                        name: "Compresor",
                        id: 6,
                    },
                    {
                        name: "Empaques a Tomas de Compresor",
                        id: 29,
                    },
                    {
                        name: "Mano de Obra",
                        id: 1,
                    },
                    {
                        name: "Servicio de A/C",
                        id: 20,
                        price: 750,
                        low_price: 750,
                        mid_price: 750,
                        high_price: 750,
                    },
                    {
                        name: "Valvulas de Servicio",
                        id: 43,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    },
                    {
                        name: "Filtro de Cabina",
                        id: 13,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    }
                ]
            };

            let lavadoSistema = {
                car: car,
                service: 10,
                items: [
                    {
                        name: "Mano de obra lavado de lineas y evaporador",
                        id: 28,
                        price: 3000,
                        low_price: 3000,
                        mid_price: 3000,
                        high_price: 3000,
                    },
                    {
                        name: "Compresor",
                        id: 6,
                    },
                    {
                        name: "Condensador",
                        id: 7,
                    },
                    {
                        name: "Filtro Dryer",
                        id: 44,
                    },
                    {
                        name: "Valvula de Expansion",
                        id: 17,
                    },
                    {
                        name: "Empaques a Tomas",
                        id: 45,
                        price: 150,
                        low_price: 150,
                        mid_price: 150,
                        high_price: 150,
                    },
                    {
                        name: "Aceite",
                        id: 2,
                        price: 155,
                        low_price: 155,
                        mid_price: 155,
                        high_price: 155,
                    },
                    {
                        name: "Gas",
                        id: 14,
                        price: 185,
                        low_price: 185,
                        mid_price: 185,
                        high_price: 185,
                    },
                    {
                        name: "Flush Limpiador",
                        id: 30,
                        price: 360,
                        low_price: 360,
                        mid_price: 360,
                        high_price: 360,
                    },
                    {
                        name: "Valvulas de Servicio",
                        id: 43,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    },
                    {
                        name: "Filtro de Cabina",
                        id: 13,
                        price: 250,
                        low_price: 250,
                        mid_price: 250,
                        high_price: 250,
                    }
                ]
            };

            return [servicioAC, lavadoSistema, reemplazoCompresor, reemplazoEvaporador, reemplazoCondensador, reemplazoManguera, reemplazoClutchCompresor, reemplazoBaleroCompresor, empacadoCompresor, cambioAceite, reemplazoBlower, cambioBobina, afinacion];
        },
        getCarServices(callback, lightMode) {
            axios.get(`/api/carservices?all`).then(function (response) {
                let services = [];
                response.data.forEach((cs) => {
                    if (cs.service.name != "indefinido") {
                        let name = cs.service.name;
                        let car = cs.car;
                        let result = services.filter(s => s.name == name);
                        if (result.length == 0) {
                            if (lightMode)
                                services.push({ name: name, cars: [car.id] });
                            else
                                services.push({ name: name, cars: [car] });
                        } else {
                            if (lightMode)
                                result[0].cars.push(car.id);
                            else
                                result[0].cars.push(car);
                        }
                    }
                });

                callback(services);
            }).catch((error) => {
                callback([]);
            });
        }
    }
};