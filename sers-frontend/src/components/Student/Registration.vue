<template>
    <div class = "container-fluid display-container">

        <div class="table-container">
            <div class="table-header">
                <h4>Courses Open for registration</h4>
                <p class = "text-muted">Courses that are available for registration</p>
            </div>
        
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Course Name</th>
                        <th scope = "col">Instructor(s)</th>
                        <th scope = "col">Class Date</th>
                        <th scope = "col">Slots Taken</th>
                        <th scope = "col">Deadline</th>
                        <th scope = "col" class = "text-center">Status</th>
                        
                    </tr>
                </thead>
                <tbody v-if="registrations.length!=0">
                    <tr v-for="course in registrations" :key="course" @click="changeStatus(course)" data-toggle="modal" data-target="#exampleModalCenterTitle">
                        <td><b>{{course.Course_Name}} - {{course.Class}}</b> <br> {{course.Run_Desc}}</td>
                        <td>{{processInstructor(course.Instructors)}}</td>
                        <td><p> <b>{{course.Run_StartDate}}</b> <br> <span class="text-muted">{{course.Run_StartTime}}</span> </p></td>
                        <td>
                            <span v-if="course.Signups">
                                {{course.Signups}}
                            </span>
                            <span v-else>
                                0
                            </span>
                            /{{course.Run_MaxSlots}}</td>
                        <td><b>{{course.End_Date}}</b></td>
                        <td class = "text-center">
                            <span :class="['badge', 'badge-pill', course.RUser_ID == $store.state.user_id? 'registered':'register']">
                                <span v-if="course.RUser_ID == $store.state.user_id">Registered</span>
                                <span v-else>Register</span>
                            </span>
                        </td>

                    </tr>
                </tbody>                
                <tbody v-else>
                    <tr>
                        <div class="table-header"><h4>No courses at the moment</h4></div>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenterTitle" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="false" v-if="registrations.length!=0">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{current.Course_Name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Date: {{current.Run_StartDate}} Time: {{current.Run_StartTime}}</p>
                        <p>Course Duration: {{current.Run_Days}} Day(s)</p>
                        <p>Course Fees: {{current.Run_Fees}}</p>
                        <p>{{current.Run_Desc}}</p>
                        <button type ="button" class="btn btn-danger"  data-dismiss="modal" v-if="current.RUser_ID == $store.state.user_id" @click="deregister(current)">Remove</button>
                        <button type ="button" class="btn register"  data-dismiss="modal" v-else @click="register()">Register</button>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    // ALLCOURSESREGISTRATIONSTUDENT_URL, 
    import Swal from 'sweetalert2'
    import {REGISTER_URL, DEREGISTER_URL} from '../../variables'
    export default {
        name: "Registration",
        data(){
            return {
                // registrations: [],
                registered: {},
                current: {}
            }
        },
        props:{
            registrations: Array
        },
        methods:{
        //    getOngoingRegistrations(){
        //         axios.post(ALLCOURSESREGISTRATIONSTUDENT_URL,
        //         {
        //         },
        //         {withCredentials: true}
        //         ).then(response => {    
        //             if (response.data === 'no rights'){
        //                 this.redirectToCourse()
        //             }else{           
        //                 this.registrations = response.data
        //             }
        //         })
        //     },

            changeStatus(course){
                this.current = course
            },
            register(){

                axios.post(REGISTER_URL,{
                    'course_id': this.current.Course_ID,
                    'run_start': this.current.Run_StartDate,
                    'class_no': this.current.Class,
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data == 'created'){
                        this.successMessage('Successfully registered')
                        this.$parent.getRegistrations()
                    }
                    
                    
                })

            },            
            deregister(course){
                console.log(course)
                axios.post(DEREGISTER_URL,{
                    'course_id': course.Course_ID,
                    'run_start': course.RRun_StartDate,
                    'class_no': course.Class
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data == 'removed'){
                        this.successMessage('Successfully unregistered')
                        this.$parent.getRegistrations()
                    }

                })
            },
            processInstructor(instructors){
                if (!instructors){
                    return instructors
                }
                return instructors.split(',').join(', ')
            },
            successMessage(msg){
                Swal.fire({
                    icon: 'success',
                    title: msg,
                    showConfirmButton: false,
                    timer: 1000
                })
            }
        },
        mounted(){
            // this.getOngoingRegistrations()           
        },
    }
</script>
<style scoped>
    .badge-pill{
        width: 100px;
    }
    :deep(.registered){
        background-color: var(--dark-gray);
        color: white;
    }
    :deep(.register){
        background-color: var(--pill-green);
        color: white;
    }
</style>