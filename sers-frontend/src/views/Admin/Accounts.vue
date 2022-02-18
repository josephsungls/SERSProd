<template>
    <div class="container-fluid  display-container">
        <div class="table-container">
            <div class="table-header">
                <h4>Unassigned Users</h4>
                <p class = "text-muted">Update their roles to be Instructors or management.</p>
            </div>
        
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">ID & Name</th>
                        <th scope = "col">Contact</th>
                        <th scope = "col">Company - Designation</th>
                        <th scope = "col">Alumni?</th>
                        <th scope = "col" class = "status text-center">Role</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in unassigned" :key="user" @click='current = user'  data-toggle="modal" data-target="#exampleModalCenter">
                        <td><b>{{user.User_ID}}</b> <br/> {{user.User_Name}}</td>
                        <td>{{user.User_Email}} <br/> {{user.Contact}}</td>
                        <td>{{user.Company}} - {{user.Designation}}</td>
                        <td>{{user.Alumni}}</td>
                        <td class = "text-center">
                            <span :class="['badge', 'badge-pill', decryptRole(user.URole_ID)]">{{decryptRole(user.URole_ID)}}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{current.User_Name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>Change Status:</h5>
                        <!-- <button type="button" class="btn active modal-btn" @click="updateStatus('active')" v-if="current.Course_Status!='active'"  data-dismiss="modal">Active</button> -->
                        <button type="button" class="btn Student modal-btn" @click="setAccountRole(current.User_ID, 1)"  data-dismiss="modal" v-if="current.URole_ID != 1">Student</button>
                        <button type="button" class="btn Admin modal-btn" @click="setAccountRole(current.User_ID, 2)"  data-dismiss="modal" v-if="current.URole_ID != 2">Admin</button>
                        <button type="button" class="btn Instructor modal-btn" @click="setAccountRole(current.User_ID, 3)"  data-dismiss="modal" v-if="current.URole_ID != 3">Instructor</button>
                        <button type="button" class="btn Management modal-btn" @click="setAccountRole(current.User_ID, 4)" data-dismiss="modal" v-if="current.URole_ID != 4">Management</button>
                    </div>
      
                </div> 
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    import {GETACCOUNTS_URL, SETACCOUNTROLE_URL} from '../../variables'
    import Swal from 'sweetalert2'
    import router from '../../router'
    export default {
        name: "Accounts",
        data(){
            return{
                unassigned: [],
                current: {}
            }
        },
        methods:{
            getAccounts(){
                axios.get(GETACCOUNTS_URL,
                {withCredentials: true}
                ).then(response => {
                    this.unassigned = response.data
                    this.current = this.unassigned[0]
                })
            },
            setAccountRole(user_id, role_id){
                axios.post(SETACCOUNTROLE_URL,{
                    'user_id': user_id,
                    'role_id': role_id,
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data == 'updated'){
                        this.getAccounts()
                        Swal.fire({
                            icon: 'success',
                            title: 'User role updated',
                            showConfirmButton: false,
                            timer: 1000
                        })
                        
                    }

                })
            },
            decryptRole(role){
                if (role == 1){
                    return 'Student'
                }else if (role == 2){
                    return 'Admin'
                }else if (role == 3){
                    return 'Instructor'
                }else if (role == 4){
                    return 'Management'
                }else{
                    return 'Pending'
                }
            }
        },
        mounted(){
            this.getAccounts()
            console.log(this.$store.state)
            if (this.$store.state.role != 2){
                router.push('/')
            }
        }
    }
</script>
<style scoped>

    .table-header{
        margin:1em;
    }
    .badge-pill{
        width: 100px;
    }

    table{
        min-width: 800px;
    }
    thead{
        top:0;
        position:sticky;
        
        background:var(--navigation-color);
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.2);
    }
    .modal-btn{
        margin:5px;
    }
    :deep(.Student){
        background-color: var(--pill-green);
        color: white;
    }
    :deep(.Management){
        background-color: var(--pill-blue);
        color: white;
    }
    :deep(.Instructor){
        background-color: var(--pill-yellow);
        color: white;
    }
    :deep(.Pending){
        background-color: var(--dark-gray);
        color: white;
    }
    :deep(.Admin){
        background-color: var(--pill-red);
        color: white;
    }

    .table-container{
        margin-top: 7em;
    }
</style>