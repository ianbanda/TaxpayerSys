<div class="w3-center loggedin" style="margin: auto;">
    {topbar}
    <div class="lowerbar w3-row">
        <div class="left w3-left w3-twothird w3-padding">
            <div class="w3-container w3-border .cont w3-white">
                <div class="w3-padding "  style="overflow-x:auto;">

                    <table class="w3-table w3-striped" style="font-size: 12px">
                        <tr class="w3-green">
                            <th>TPIN</th>
                            <th>Trading Name</th>
                            <th>Business Certificate Number</th>
                            <th>Mobile Number</th>
                            <th>Physical Location</th>
                            <th>Username</th>
                        </tr>
                        <!-- START tplist -->
                        <tr>
                            <td>{TPIN}</td>
                            <td>{TradingName}</td>
                            <td>{BusinessCertificateNumber}</td>
                            <td>{MobileNumber}</td>
                            <td>{PhysicalLocation}</td>
                            <td>{Username}</td>
                        </tr>
                        <!-- END tplist -->
                    </table>

                </div>
                <div class="w3-row w3-padding more">
                    <a href="Taxpayers/add" class="w3-button w3-dark-grey w3-left w3-margin-right">+ New Taxpayer</a>
                    <a href="Taxpayers" class="w3-button w3-green w3-left">Manage Tax Payers</a>
                </div>
            </div>

        </div>
        <div class="right w3-row w3-left">
            {rightbit}
        </div>
    </div>
</div>