<template>
    <div class="table-container">
        <table class = "table">
            <thead>
                <tr>
                    <th scope = "col">Course Code & Name</th>
                    <th scope = "col">Course Desc</th>
                    <th scope = "col">Instructor</th>
                    <th scope = "col">Slots Taken</th>
                    <th scope = "col">Registration End Date</th>
                    <th scope = "col" class = "status text-center">Status</th>
                </tr>
            </thead>
            <tbody v-if="registrations.length != 0">
                <tr v-for="course in registrations" :key="course">
                    <td  @click="enterCourse(course)"><b>{{course.Course_ID}}</b> <br> {{course.Course_Name}} - Class:{{course.Class}}</td>
                    <td  @click="enterCourse(course)">  <br> {{course.Run_Desc}}</td>
                    <td  @click="enterCourse(course)"> <br> {{processInstructors(course.User_Name)}}</td>
                    <td  @click="enterCourse(course)"> <br> {{course.No_Registrations}}/{{course.Run_MaxSlots}}</td>
                    <td> <br>
                        <span class="badge badge-pill updated" data-toggle="modal" data-target="#changeRegEnd" @click="changeCurrent(course)" > 
                            {{course.Registration_End}}
                        </span>
                    </td>
                    <td class = "text-center">
                        <br> 
                        <span class="badge badge-pill badge-warning"  data-toggle="modal" data-target="#changeStatus"  @click="changeCurrent(course)" > 
                            {{course.Run_Status}}
                       </span>
                    </td>
                    
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan='6' class="pt-5 text-center" >
                        <h4 style="font-family: 'Mulish', sans-serif;">No courses at the moment</h4>
                    </td>
                </tr>
            </tbody>            
        </table>
        <!-- Change Date Modal -->
        <div class="modal fade" id="changeRegEnd" tabindex="-1" role="dialog" aria-labelledby="changeRegEnd" aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" v-if="current!=[]">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{current.Course_ID}} - {{current.Course_Name}}</h5>
                        <button type="button" id="close" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><b>Course Description:</b></p>
                        <p>{{current.Course_Desc}}</p>  
                        <form>
                            <label for="start">Registration End Date:</label>
                            <input type="date" id="end"
                                v-model="registration_end">
                        </form>
                        <br/>
                        <button type ="button" class="btn btn-success mr-2"  @click="changeDate()" data-dismiss="modal" v-if="current.FCourse_Status != 'offered'">Change Date</button>
                        
                    </div>
                </div> 
            </div>
        </div>
        <!-- Change Status Modal -->
        <div class="modal fade" id="changeStatus" tabindex="-1" role="dialog" aria-labelledby="changeStatus" aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" v-if="current!=[]">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{current.Course_ID}} - {{current.Course_Name}}</h5>
                        <button type="button" id="close" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><b>Course Description:</b></p>
                        <p>{{current.Course_Desc}}</p>  
                        <br/>
                        <p><b>Change Status:</b></p>
                        <button type ="button" class="btn btn-success mr-2"  @click="confirmStatusChange('offered')" data-dismiss="modal">Offer</button>
                        <button type ="button" class="btn btn-danger mr-2"  @click="confirmStatusChange('closed')" data-dismiss="modal">Close</button>
                    </div>
                </div> 
            </div>
        </div>
    </div>

</template>
<script>
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import router from '../../router'
    import {ONGOINGREGISTRATIONS_URL, OPENREGISTRATION_URL, CHANGERUNSTATUS_URL} from '../../variables'
    export default{
        name: 'OngoingRegistration',
        data(){
            return {
                current: [],
                registrations: [],
                registration_end: ''
            }
        },
        methods:{
            getOngoingRegistrations(){
                axios.get(ONGOINGREGISTRATIONS_URL).then(response => {
                    this.registrations = response.data
                    if (response.data.length > 0){
                        
                        this.current = this.registrations[0],
                        this.registration_end = this.current.Registration_End
                    }
                    
                })
        
            },
            enterCourse(course){
                this.$parent.current = course
                this.$parent.individualCourse()
            },

            changeDate(){
                axios.post(OPENREGISTRATION_URL,{
                    'course': this.current,
                    'reg_end': this.registration_end
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data == 'no rights'){
                        this.redirectToCourse()
                    }
                    else if (response.data == 'updated'){
                        Swal.fire({
                        icon: 'success',
                        title: 'Registration end date changed!',
                        })
                        this.getOngoingRegistrations()
                    }
                    

                })
            },
            changeCurrent(course){
                this.current = course
                this.registration_end = course.Registration_End
            },

            changeStatus(status){
                axios.post(CHANGERUNSTATUS_URL,{
                    'course': this.current,
                    'status': status
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data == 'no rights'){
                        this.redirectToCourse()
                    }
                    else if (response.data == 'updated'){
                        Swal.fire({
                            icon: 'success',
                            title: 'Course status has been changed!',
                        })
                        this.getOngoingRegistrations()
                    }


                })
            },
            processInstructors(instructors){
                if (!instructors){
                    return instructors
                }
                return instructors.split(',').join(', ')
            },
            redirectToCourse(){
                let timerInterval
                Swal.fire({
                icon: 'error',
                title: 'Access denied!',
                html: 'You do not have the access rights! Redirecting...',
                timer: 3000,
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
            confirmStatusChange(status){
                if (status == 'offered'){
                    Swal.fire({
                        title: 'Are you sure?',
                        html: "By offering the run, you will no longer be able to edit the run details. <br/>This action is <b>IRREVERSIBLE!</b> <br/><br/> <b>Note: </b>Next step is to accept and reject the students who registered from the student list.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Offer!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.changeStatus(status)
                        }
                    })
                }else{
                    Swal.fire({
                        title: 'Are you sure?',
                        html: "By closing the course, it means that the course will not be conducted! <br/>This action is <b>IRREVERSIBLE!</b>",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Close!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.changeStatus(status)
                        }
                    })
                }
            }
        },
        mounted(){
            this.getOngoingRegistrations()

        }
        
    }
</script>
<style scoped>
    thead{
        position:sticky;
        top:0;
        background-color: white;
    }
    .badge-pill{
        width: 100px;
    }
    .badge{
        transition:0.2s;
    }
    .badge:hover{
        transform: translateY(-3px);
    }
    :deep(.pending){
        background-color: var(--pill-yellow);
        color: white;
    }
    :deep(.updated){
        background-color: var(--pill-green);
        color: white;
    }
    :deep(.gray){
        background-color: var(--gray);
        color: white;
    }
    .badge-warning{
        color:white;
    }
    .table-container{
        padding: 0px;
    }

    td:nth-child(1){
        white-space:pre-wrap;
    }
    
    tbody tr:hover {
        background-color: rgba(78, 47, 49, 0.767);
    }

    .badge-pill{
        padding: 9px;
        border-radius: 50px;
        word-wrap: break-word;
    }

    /* float middle of row (vertically) */
    .middle-of-row{
        vertical-align: middle;
        text-align: center;
    }

    .badge-pill::after{
        box-shadow: 2px;
    }

    .instructor-pill{
        width: 4cm;
    }


</style>