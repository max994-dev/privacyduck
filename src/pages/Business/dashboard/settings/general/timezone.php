<select id="timezone" name="timezone" onchange="changeTimezone()" class="block w-72 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
    <option value="Pacific/Midway">(UTC-11:00) Pacific/Midway</option>
    <option value="Pacific/Niue">(UTC-11:00) Pacific/Niue</option>
    <option value="Pacific/Pago_Pago">(UTC-11:00) Pacific/Pago_Pago</option>
    <option value="Pacific/Honolulu">(UTC-10:00) Pacific/Honolulu</option>
    <option value="Pacific/Johnston">(UTC-10:00) Pacific/Johnston</option>
    <option value="Pacific/Rarotonga">(UTC-10:00) Pacific/Rarotonga</option>
    <option value="Pacific/Tahiti">(UTC-10:00) Pacific/Tahiti</option>
    <option value="America/Anchorage">(UTC-09:00) America/Anchorage</option>
    <option value="America/Juneau">(UTC-09:00) America/Juneau</option>
    <option value="America/Nome">(UTC-09:00) America/Nome</option>
    <option value="America/Sitka">(UTC-09:00) America/Sitka</option>
    <option value="America/Yakutat">(UTC-09:00) America/Yakutat</option>
    <option value="Pacific/Gambier">(UTC-09:00) Pacific/Gambier</option>
    <option value="America/Los_Angeles">(UTC-08:00) America/Los_Angeles</option>
    <option value="America/Tijuana">(UTC-08:00) America/Tijuana</option>
    <option value="America/Vancouver">(UTC-08:00) America/Vancouver</option>
    <option value="America/Whitehorse">(UTC-08:00) America/Whitehorse</option>
    <option value="Pacific/Pitcairn">(UTC-08:00) Pacific/Pitcairn</option>
    <option value="America/Denver">(UTC-07:00) America/Denver</option>
    <option value="America/Edmonton">(UTC-07:00) America/Edmonton</option>
    <option value="America/Hermosillo">(UTC-07:00) America/Hermosillo</option>
    <option value="America/Mazatlan">(UTC-07:00) America/Mazatlan</option>
    <option value="America/Phoenix">(UTC-07:00) America/Phoenix</option>
    <option value="America/Yellowknife">(UTC-07:00) America/Yellowknife</option>
    <option value="America/Belize">(UTC-06:00) America/Belize</option>
    <option value="America/Chicago">(UTC-06:00) America/Chicago</option>
    <option value="America/Costa_Rica">(UTC-06:00) America/Costa_Rica</option>
    <option value="America/El_Salvador">(UTC-06:00) America/El_Salvador</option>
    <option value="America/Guatemala">(UTC-06:00) America/Guatemala</option>
    <option value="America/Managua">(UTC-06:00) America/Managua</option>
    <option value="America/Mexico_City">(UTC-06:00) America/Mexico_City</option>
    <option value="America/Regina">(UTC-06:00) America/Regina</option>
    <option value="America/Tegucigalpa">(UTC-06:00) America/Tegucigalpa</option>
    <option value="America/Winnipeg">(UTC-06:00) America/Winnipeg</option>
    <option value="Pacific/Galapagos">(UTC-06:00) Pacific/Galapagos</option>
    <option value="America/Bogota">(UTC-05:00) America/Bogota</option>
    <option value="America/Cancun">(UTC-05:00) America/Cancun</option>
    <option value="America/Cayman">(UTC-05:00) America/Cayman</option>
    <option value="America/Detroit">(UTC-05:00) America/Detroit</option>
    <option value="America/Eastern">(UTC-05:00) America/Eastern</option>
    <option value="America/Havana">(UTC-05:00) America/Havana</option>
    <option value="America/Iqaluit">(UTC-05:00) America/Iqaluit</option>
    <option value="America/Jamaica">(UTC-05:00) America/Jamaica</option>
    <option value="America/Lima">(UTC-05:00) America/Lima</option>
    <option value="America/Nassau">(UTC-05:00) America/Nassau</option>
    <option value="America/New_York">(UTC-05:00) America/New_York</option>
    <option value="America/Panama">(UTC-05:00) America/Panama</option>
    <option value="America/Port-au-Prince">(UTC-05:00) America/Port-au-Prince</option>
    <option value="America/Rio_Branco">(UTC-05:00) America/Rio_Branco</option>
    <option value="Europe/London">(UTC+00:00) Europe/London</option>
    <option value="Europe/Amsterdam">(UTC+01:00) Europe/Amsterdam</option>
    <option value="Europe/Berlin">(UTC+01:00) Europe/Berlin</option>
    <option value="Europe/Madrid">(UTC+01:00) Europe/Madrid</option>
    <option value="Europe/Paris">(UTC+01:00) Europe/Paris</option>
    <option value="Europe/Rome">(UTC+01:00) Europe/Rome</option>
    <option value="Europe/Zurich">(UTC+01:00) Europe/Zurich</option>
    <option value="Europe/Kiev">(UTC+02:00) Europe/Kiev</option>
    <option value="Europe/Athens">(UTC+02:00) Europe/Athens</option>
    <option value="Europe/Helsinki">(UTC+02:00) Europe/Helsinki</option>
    <option value="Europe/Istanbul">(UTC+03:00) Europe/Istanbul</option>
    <option value="Europe/Moscow">(UTC+03:00) Europe/Moscow</option>
    <option value="Asia/Dubai">(UTC+04:00) Asia/Dubai</option>
    <option value="Asia/Karachi">(UTC+05:00) Asia/Karachi</option>
    <option value="Asia/Kolkata">(UTC+05:30) Asia/Kolkata</option>
    <option value="Asia/Dhaka">(UTC+06:00) Asia/Dhaka</option>
    <option value="Asia/Bangkok">(UTC+07:00) Asia/Bangkok</option>
    <option value="Asia/Shanghai">(UTC+08:00) Asia/Shanghai</option>
    <option value="Asia/Tokyo">(UTC+09:00) Asia/Tokyo</option>
    <option value="Australia/Sydney">(UTC+10:00) Australia/Sydney</option>
</select>
<script>
    function timezone() {
        const timezoneSelect = document.getElementById('timezone');
        const browserTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        if (localStorage.getItem("timezone")) {
            timezoneSelect.value = localStorage.getItem("timezone");
        } else {
            for (let i = 0; i < timezoneSelect.options.length; i++) {
                if (timezoneSelect.options[i].value === browserTimezone) {
                    timezoneSelect.selectedIndex = i;
                    break;
                }
            }
        }
    }

    function changeTimezone() {
        const timezoneSelect = document.getElementById('timezone');
        const selectedTimezone = timezoneSelect.options[timezoneSelect.selectedIndex].value;
        localStorage.setItem("timezone", selectedTimezone);
    }
</script>