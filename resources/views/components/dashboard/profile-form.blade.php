<div class="container">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>User Profile</h4>
                    <hr />
                    <div class="container-fluid m-0 p-0">
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <label>Email Address</label>
                                <input readonly id="email" placeholder="User Email" class="form-control"
                                    type="email" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>First Name</label>
                                <input id="firstName" placeholder="First Name" class="form-control" type="text" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Last Name</label>
                                <input id="lastName" placeholder="Last Name" class="form-control" type="text" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Mobile Number</label>
                                <input id="mobile" placeholder="Mobile" class="form-control" type="mobile" />
                            </div>
                        </div>
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <button onclick="onUpdate()" class="btn mt-3 w-100  bg-gradient-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    getProfile();
    async function getProfile() {
        try {
            showLoader();
            let res = await axios.get("/user-profile", HeaderToken());
            hideLoader();
            document.getElementById('email').value = res.data['email'];
            document.getElementById('firstName').value = res.data['firstName'];
            document.getElementById('lastName').value = res.data['lastName'];
            document.getElementById('mobile').value = res.data['mobile'];

        } catch (e) {
            unauthorized(e.response.status)
        }
    }

    async function onUpdate() {
        let PostBody = {
            "firstName": document.getElementById('firstName').value,
            "lastName": document.getElementById('lastName').value,
            "mobile": document.getElementById('mobile').value,
        }
        showLoader();
        let res = await axios.post("/user-update", PostBody, HeaderToken());
        hideLoader();
        if (res.data['status'] === "success") {
            successToast(res.data['message'])
            await getProfile();
        } else {
            successToast(res.data['message'])
        }
    }
</script>
