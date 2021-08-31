<template>
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                  <div class="row">
                       <div class="col-md-6 col-sm-12 ">
                        {{ statusLabel }}
                       </div>
                       <div class="col-md-6 col-sm-12 text-right">
                           <div class="row">
                                <div class="col-md-6 col-sm-12 ">
                                    <a href="javascript:void(0)" v-on:click="fetchData()" class="btn btn-warning btn-sm btn-block">Update Record</a>
                                </div>
                               <div class="col-md-6 col-sm-12">
                                   <a href="javascript:void(0)" v-on:click="clearStorage()" class="btn btn-primary btn-sm btn-block">Clear Local Storage</a>
                               </div>
                           </div>
                       </div>
                  </div>
                </div> 
                <div class="card-body">
                    <div class="row">
                        <div v-if="loading">Loading please wait...</div>
                        <div class="col-md-3 user" v-for="record in records" v-if="!loading">
                             <a href="javascript:void(0)" v-on:click="selectRecord(record)">
                                 <img :src="record.owner.avatar_url" class="card-img-top img-responsive" :alt="record.owner.login"  >
                             </a>
                             <p class="card-text">
                                <h5>{{ record.owner.login }}</h5>
                                Comments: {{ record.comments }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       <!-- Modal -->
        <div class="modal fade" id="modalDiplay" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div v-if="loadingInfo" class="text-center">
                   <div class="spinner-border" role="status">
                      <span class="visually-hidden"></span>
                    </div>
                </div>

                <div class="card" v-if="!loadingInfo">
                  <div class="card-body">
                      <img class="card-img-top" :src="userInfo.avatar_url" alt="Card image cap">
                     <ul class="list-group list-group-flush">
                        <li class="list-group-item">Name: {{ userInfo.name? userInfo.name: userInfo.login}}</li>
                        <li class="list-group-item" v-if="userInfo.location">Location: {{ userInfo.location }}</li>
                        <li class="list-group-item" v-if="userInfo.bio">Bio: {{ userInfo.bio }}</li>
                        <li class="list-group-item"><a :href="userInfo.html_url" target="_blank">Visit my GitHub Account</a></li>
                      </ul>

                  </div>
                </div>
              
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div>
            </div>
          </div>
        </div>

    </div>
</div>
</template>

<script>
    export default {
        data() {
          return {
            statusLabel: "Records",
            loading: false,
            records: {},   
            userInfo: {},
            loadingInfo: false,
          }
        },
        // mounted() {
        //    // this.updateLocaleStorage();
        // },
        created() {
            // when loads and if it's empty then request from the api
            if (!localStorage.records) {
              this.fetchData();
            }
            //  when reload it capture from localstorage
            if (localStorage.records) {
              this.records = JSON.parse(localStorage.records);
            }
        },  
        methods: {
            fetchData: function() {
                //
                this.loading = true;
                this.statusLabel = "Updating content";
                //
                let url = 'https://api.github.com/gists/public';
                //
                axios.get(url).then((res) => {
                    this.statusLabel = "Content updated";
                    localStorage.records = JSON.stringify(res.data);
                    this.records = res.data;
                    this.loading = false;
                }).catch((errors) => {
                    this.loading = false;
                    alert(errors);
                }); 
            },
            selectRecord: function(user) {
                this.loadingInfo = true;
                let url = user.owner.url;
                $('#modalDiplay').modal()   
                axios.get(url).then((res) => {
                    this.userInfo = res.data;
                    this.loadingInfo = false;
                }).catch((errors) => {
                    this.loadingInfo = false;
                    alert(errors);
                }); 
            },
            clearStorage: function() {
                localStorage.removeItem('records');
                this.fetchData();
            }
        }
    }
</script>
