<template>
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                  <div class="row">
                       <div class="col-md-6 col-sm-12 ">
                        Search Word
                       </div>
                  </div>
                </div> 
                <div class="card-body">
                    <div class="row">
                      
                        <div class="col-md-12">
                            <div class="form-group">
                              <label for="exampleInputEmail1">Search</label>
                              <input type="text" class="form-control" placeholder="Enter a word" v-model="field.search">
                            </div>
                            <button type="button" class="btn btn-primary" v-on:click="search()">Submit</button>
                        </div>
                        <br>
                        <div v-if="loading" class="loading">Loading please wait...</div>
                        <div class="col-md-12 " v-if="!loading && results.length >0">
                          <hr>
                          You have: {{ results.length }} result/s
                          <ul>
                            <li v-for="result in results">{{ result.definition }}</li>
                          </ul>
                        </div>
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
            field: {
              search: "",
            },
            statusLabel: "Records",
            loading: false,
            loadingInfo: false,
            results: [],
          }
        },
        created() {
           
        },  
        methods: {
            search: function() { 
                // 
                let searchString = this.field.search.toString().toLowerCase();
                //
                if (this.validatedString(searchString)) {
                  this.results = [];
                  this.loading = true;
                  this.statusLabel = "Updating content";
                  //
                  let url = 'api/search';
                  //
                  axios.post(url, {
                    search: searchString,
                  }).then((res) => {
                      this.results = res.data.results;
                      this.statusLabel = "Content updated";
                      this.loading = false;
                  }).catch((errors) => {
                      this.loading = false;
                      // display alert 
                      alert(errors);
                  }); 
                }
            },
            validatedString(stringString) {
              console.log(stringString);
              if (stringString=="") {
                alert('Invalid search, please enter a word.');
                return false;
              }
              else {
                return true;
              }

            }
            
        }
    }
</script>
