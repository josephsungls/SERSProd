<template>
    <div class = "container-fluid display-container" v-if="access">

        <div class="table-container">
            <div class="table-header">
                <h4>Proposed Courses</h4>
                <p class = "text-muted">Student proposed courses</p>
            </div>
        
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Course Name</th>
                        <th scope = "col">Course Description</th>
                        <th scope = "col">Proposer Information</th>
                        <th scope = "col">Proposal status</th>
                        
                    </tr>
                </thead>
                <tbody v-if="proposals.length">
                    <template v-for="course in proposals" >
                        <tr :key="course.CP_Name" @click="changeStatus(course)" data-toggle="modal" data-target="#changeStatus" v-if="role == 2">
                            <td><b>{{course.CP_Name}}</b></td>
                            <td>{{course.CP_Desc}}</td>
                            <td><b>{{course.User_ID}}</b> <br/> {{course.User_Email}}</td>
                            <td>
                                <span :class="['badge', 'badge-pill', course.CP_Status == 'reviewing'? 'reviewing': course.CP_Status == 'approved'? 'approved': 'rejected']" @click="changeStatus(course)" data-toggle="modal" data-target="#changeStatus">
                                    <span>{{course.CP_Status}}</span>
                                </span>
                            </td>

                        </tr>
                        <tr :key="course" v-else>
                            <td><b>{{course.CP_Name}}</b></td>
                            <td>{{course.CP_Desc}}</td>
                            <td><b>{{course.User_ID}}</b> <br/> {{course.User_Email}}</td>
                            <td>
                                <span :class="['badge', 'badge-pill', course.CP_Status == 'reviewing'? 'reviewing': course.CP_Status == 'approved'? 'approved': 'rejected']">
                                    <span>{{course.CP_Status}}</span>
                                </span>
                            </td>

                        </tr>
                    </template>
                </tbody>                
                <tbody v-else>
                <!-- <tbody> -->
                    <tr>
                        <div class="table-header"><h4>No courses at the moment</h4></div>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="changeStatus" tabindex="-1" role="dialog" aria-labelledby="changeStatus" aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{current.CP_Name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><b>Course Description:</b></p>
                        <p>{{current.CP_Desc}}</p>
                        <p><b>Proposed By:</b></p>
                        <p>{{current.User_ID}} - {{current.User_Email}}</p>    
                        <button type ="button" class="btn btn-success mr-2"  data-dismiss="modal" @click="update('approved')">Approve</button>
                        <button type ="button" class="btn btn-danger"  data-dismiss="modal" @click="update('rejected')">Reject</button>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</template>
<script>
    // import router from '../../router'
    import {GETPROPOSALS_URL, UPDATEPROPOSAL_URL, GETUSERROLE_URL} from '../../variables'
    import Swal from 'sweetalert2'
    import router from '../../router.js'
    import axios from 'axios'
    export default{
        data(){
            return{
                proposals: [],
                role: false,
                current: [],
                access: false
            }
        },
        methods:{
            getProposals(){
                axios.get(GETPROPOSALS_URL).then(response => (
                    this.proposals = response.data,
                    this.current = this.proposals[0]
                    
                ))
            },
            changeStatus(course){
                this.current = course
            },
            update(status){
                axios.post(UPDATEPROPOSAL_URL,{
                    'proposal_id': this.current.CP_ID,
                    'status' : status
                },
                {withCredentials: true}
                ).catch(function(error){
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data == 'updated'){
                        this.getProposals()
                    }else if (response.data === 'no rights'){
                        let timerInterval
                        Swal.fire({
                            icon: 'error',
                            title: 'Access denied!',
                            html: 'You do not have the access rights!',
                            timer: 1000,
                            timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                        })
                    }
                })
            },
            checkUserRole(){
                axios.get(GETUSERROLE_URL,
                {withCredentials: true}                
                ).then(response=>{
                    
                    if (response.data != 2 && response.data != 4){
                        this.redirectToCourse()
                    }else{
                        this.role = response.data    
                        this.getProposals()
                        this.access = true
                    }
                })
            },
            redirectToCourse(){
                let timerInterval
                Swal.fire({
                icon: 'error',
                title: 'Access denied!',
                html: 'You do not have the access rights! Redirecting...',
                timer: 1000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
                })
                router.push('/courses')
            },
        },
        mounted(){
            this.checkUserRole()
        }
    }
</script>
<style scoped>
    .badge-pill{
        width: 100px;
        transition:0.2s;
    }
    .badge-pill:hover{
        transform: translateY(-3px);
    }
    :deep(.approved){
        background-color: var(--pill-green);
        color: white;
    }
    :deep(.rejected){
        background-color: var(--pill-red);
        color: white;
    }
    :deep(.reviewing){
        background-color: var(--gray);
        color: white;
    }
    td {
        transition:0.1s;
        cursor: pointer;
    }
    tr:hover > td{
        background-color:var(--button-color);
        color:var(--navigation-color);
    }
    .table-container{
         margin-top: 7em;
    }
</style>