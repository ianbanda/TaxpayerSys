<div class="w3-center loggedin" style="margin: auto;">
    {topbar}
    <div class="lowerbar w3-row">
        <div class="left w3-left w3-twothird w3-padding">
            <div class="w3-container w3-white w3-border .cont">
                <form class="w3-container" id="edittpform" method="POST" action="edit">
                    <div class="w3-padding  w3-left-align">
                        <h4>
                            <div class="w3-row">
                                <div class="w3-left">Edit Taxpayer Form</div>
                                <div class="w3-right w3-text-orange" style="font-size: 14px;">{formerror}</div>
                            </div>
                        </h4>
                        <input type="hidden" name="editTaxpayerForm" />
                        <input type="hidden" name="editTPForm" />
                        <label class="w3-text-green"><b>TPIN</b></label><span class="w3-margin-left">{tpin}</span>
                        <input class="w3-input w3-border" name="editTPIN" type="hidden" value="{tpin}">
                        <br/>
                        <br/>
                        <label class="w3-text-green"><b>Email</b></label>
                        <input class="w3-input w3-border" name="editEmail" type="email" value="{editEmail}">
                        {editEmail_error}
                        <label class="w3-text-green"><b>Username</b></label>
                        <input class="w3-input w3-border" name="editUsername" type="text" value="{editUsername}">
                        {editUsername_error}
                        <label class="w3-text-green"><b>Physical Location</b></label>
                        <input class="w3-input w3-border" name="editPhysicalLocation" type="text" value="{editPhysicalLocation}">
                        {editPhysicalLocation_error}
                        <label class="w3-text-green"><b>Business Certificate Number</b></label>
                        <input class="w3-input w3-border" name="editBusinessCertificateNumber" type="text" value="{editBusinessCertificateNumber}">
                        {editBusinessCertificateNumber_error}
                        <label class="w3-text-green"><b>Trading Name</b></label>
                        <input class="w3-input w3-border" name="editTradingName" type="text" value="{editTradingName}">
                        {editTradingName_error}
                        <label class="w3-text-green"><b>Business Registration Date</b></label>
                        <input class="w3-input w3-border" name="editBusinessRegistrationDate" type="text" value="{editBusinessRegistrationDate}">
                        {editBusinessRegistrationDate_error}
                        <label class="w3-text-green"><b>Mobile Number</b></label>
                        <input class="w3-input w3-border" name="editMobileNumber" type="text" value="{editMobileNumber}" >                               
                        {editMobileNumber_error}
                    </div>
                    <div class="w3-row w3-padding more">
                        <button class="w3-button w3-black w3-left" onclick="document.getElementById('edittpform').submit();">Submit Edit</button>
                        <a href="../Taxpayers" class="w3-button w3-green w3-left w3-margin-left">- Cancel Edit</a>
                    </div>
                </form>
            </div>

        </div>
        <div class="right w3-quarter">
            {rightbit}
        </div>
    </div>
</div>