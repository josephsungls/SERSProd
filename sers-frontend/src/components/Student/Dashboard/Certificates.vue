<template>
    <div class = "container-fluid  display-container">
    <button type="button" class ="add-course" @click="AddCertificate()">
        <i class="fas fa-plus"></i>Add New Certificate
    </button>
      <div class="table-container">
            
            <div class="table-header">
                <h4>Certificates obtained</h4>
                <p class = "text-muted">Upload your certificates</p>
            </div>
            
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Title & Issuing Organisation</th>
                        <th scope = "col">Certificate ID</th>
                        <th scope = "col">Date of issue</th>
                        <th scope = "col">Expiration</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="certificate in certificates" :key="certificate">
                        <td>
                            <b>{{certificate.Title}}</b>
                            <br/>
                            <small class='text-muted'>{{certificate.Organisation}}</small>
                        </td>
                        <td>
                            {{certificate.Certificate_ID}}
                            <br/>
                            <small class='text-muted'>{{certificate.Certificate_URL}}</small>
                        </td>
                        <td>
                            {{certificate.Issue_Date}}
                        </td>
                        <td>
                            {{certificate.Expiration}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    import {GETCERTIFICATES_URL} from '../../../variables'
    export default{
        name: 'Certificates',
        data(){
            return{
                certificates: []
            }
        },
        methods:{
            AddCertificate(){
                this.$parent.addCert()
            },
            getCerts(){
                axios.post(GETCERTIFICATES_URL,{
                    // 'user_id': this.$store.state.user_id,    
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    console.log(response.data)
                    this.certificates = response.data
                })
            }
        },
        mounted(){
            this.getCerts()
        }
    }
</script>
<style scoped>
    .add-course{
        display:flex;
        width: 159px;
        height: 52px;
        font-weight: bold;
        border-radius: 10px;
        background-color: var(--navigation-color);
        border: 2px solid var(--light-gray);
        margin-bottom: 20px;
        transition: 0.2s;
        /* margin:0; */

    }
    .add-course a{
        text-decoration: none;
        display:flex;
        color: var(--font-color);
        transition:0.2s;
    }
    .add-course:hover a{
        color: var(--navigation-color);
    }
    .add-course:hover,
    .add-course:hover i
     {
        color: var(--navigation-color);
        background-color: var(--button-color);
        border: 2px solid var(--navigation-color);
        /*  */
    }
    .add-course i{  
        margin-left: 5px;
        align-self: center;
        border: 2px solid var(--button-color);
        padding:3px;
        color:var(--button-color);
        border-radius: 50%;
        transition: 0.2s;
    }
    .add-course:focus{
        outline:none;
        box-shadow:none;
    }
</style>