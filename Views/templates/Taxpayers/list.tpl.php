{topbar}
<div class="lowerbar w3-row w3-padding-24" style="width:100%">
                <div class="left w3-padding w3-margin">
                    <h4>Taxpayers List <span class="w3-text-green" style="font-size: 12px; margin-left: 50px">{remark}</span></h4>
                    <div class="w3-container w3-border .cont w3-white" >
                        <form id="editForm" method="POST" action="Taxpayers/edit" style="display:none;">
                                
                        </form>
                        <div class="" style="overflow-x:auto;overflow-y:auto;">
                            <table class="w3-table w3-striped" style="font-size: 12px">
                                <tr class="w3-green">
                                    <th>TPIN</th>
                                    <th>Trading Name</th>
                                    <th>Business Certi Num</th>
                                    <th>Mobile Number</th>
                                    <th>Physical Location</th>
                                    <th>Business Reg date</th>
                                    <th>Email</th>
                                    <th style="width:0%">Username</th>
                                    <th></th>
                                </tr>
                                
                                <!-- START listTaxpayers -->
                                <tr id="row{TPIN}">
                                    <td class="editTPIN">{TPIN}</td>
                                    <td class="editTradingName">{TradingName}</td>
                                    <td class="editBusinessCertificateNumber">{BusinessCertificateNumber}</td>
                                    <td class="editMobileNumber">{MobileNumber}</td>
                                    <td class="editPhysicalLocation">{PhysicalLocation}</td>
                                    <td class="editBusinessRegistrationDate">{BusinessRegistrationDate}</td>
                                    <td class="editEmail">{Email}</td>
                                    <td class="editUsername">{Username}</td>
                                    <td>
                                        <a href="#" class="w3-text-green" onclick='editTaxpayer("{TPIN}")'>Edit</a><br/>
                                        <a href="Taxpayers/delete?tpin={TPIN}" class="w3-text-red">Delete</a>
                                    </td>
                                </tr>
                                <!-- END listTaxpayers -->

                            </table>
                        </div>
                        <div class="w3-row w3-padding more">
                            <a href="Taxpayers/add" class="w3-button w3-dark-grey w3-text-white w3-round w3-left w3-card">+ New Taxpayer</a>
                        </div>
                    </div>

                </div>
                
            </div>