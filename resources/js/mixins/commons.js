/* You can define here your global methods 
 *
 */

export default {
    methods: {
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
                case 1:
                    return "Lunes";
                case 2:
                    return "Martes";
                case 3:
                    return "Miercoles";
                case 4:
                    return "Jueves";
                case 5:
                    return "Viernes";
                case 6:
                    return "Sabado";
                case 7:
                    return "Domingo";
            }
        },
    }
};