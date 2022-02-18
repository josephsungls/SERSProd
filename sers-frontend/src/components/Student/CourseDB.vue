<template>
    <div class = "container-fluid display-container">
        <div class="table-container">
            <div class="table-header">
                <h4>All Courses</h4>
                <p class = "text-muted">Courses that are open for registration and interest indication.</p>
            </div>
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Course Code & Name</th>
                        <th scope = "col">Instructor(s)</th>
                        <th scope = "col">Date</th>
                        <th scope = "col">Slots Taken/<br/>Interest Count</th>
                        <th scope = "col">Deadline</th>
                        <th scope = "col" class = "text-center">Status</th>
                        
                    </tr>
                </thead>
                <tbody v-if="allCourses.length > 0">
                    <template  v-for="course in allCourses" >
                        
                        <tr v-if="course.Run_MaxSlots" :key="course.Run_MaxSlots" @click="changeStatus(course)" data-toggle="modal" data-target="#registrationModal">
                            <td>
                                <b>
                                    {{course.Course_ID}} 
                                </b> 
                                <br> 
                                {{course.Course_Name}}- Class:{{course.Class}}
                            </td>
                            <td> {{processInstructor(course.Instructors)}}</td>
                            
                            <td>
                                {{course.Run_StartDate}}
                                {{course.Vote_Start}}
                                <br> 
                                <small class="text-muted">{{course.Run_StartTime}}</small>
                            </td>
                            <td>
                                <span v-if="course.Signups">
                                    {{course.Signups}}
                                </span>
                                <span v-else>
                                    0
                                </span>
                                / {{course.Run_MaxSlots}}
                            </td>
                            <td>
                                {{course.End_Date}}
                            </td>
                            <td class = "text-center">
                                <span :class="['badge', 'badge-pill', course.RUser_ID == $store.state.user_id? 'registered':'register']">
                                    <span v-if="course.RUser_ID == $store.state.user_id">Registered</span>
                                    <span v-else>Register</span>
                                </span>
                            </td>
                        </tr>
                        <tr v-else :key="course" @click="changeStatus(course)" data-toggle="modal" data-target="#indicateInterest">
                            <td>
                                <b>
                                    {{course.Course_ID}} 
                                </b> 
                                <br> 
                                {{course.Course_Name}}
                            </td>
                            <td> - </td>
                            <td>
                                {{course.Vote_Start}}
                            </td>
                            <td>
                                <span v-if="course.No_Interest">
                                    {{course.No_Interest}}
                                </span>
                                <span v-else>
                                    0
                                </span>
                            </td>
                            <td>
                                {{course.End_Date}}
                            </td>
                            <td class = "text-center">
                                <span :class="['badge', 'badge-pill' , course.IUser_ID == $store.state.user_id? 'indicated':'interested']">

                                    <span v-if="course.IUser_ID == $store.state.user_id">Indicated</span>
                                    <span v-else>Interested?</span>

                                </span>
                            </td>
                        </tr>
                        
                    </template>
                </tbody>
            </table>


        </div>

        <!-- Registration Modal -->
        <div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="registrationModal" aria-hidden="true" data-backdrop="false" v-if="registration.length!=0">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{current.Course_Name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-if="$store.state.authenticated">
                            <p>Date: {{current.Run_StartDate}} Time: {{current.Run_StartTime}}</p>
                            <p>Course Duration: {{current.Run_Days}} Day(s)</p>
                            <p>Course Fees: {{current.Run_Fees}}</p>
                            <p>{{current.Run_Desc}}</p>
                            <button type ="button" class="btn btn-danger"  data-dismiss="modal" v-if="current.RUser_ID == $store.state.user_id" @click="deregister(current)">Remove</button>
                            <button type ="button" class="btn register"  data-dismiss="modal" v-else @click="register()">Register</button>
                        </div>
                        <p v-else>
                            Please log in <router-link to="/login">here</router-link>
                        </p>
                    </div>
                </div> 
            </div>
        </div>
        <!--Interest Modal -->
        <div class="modal fade" id="indicateInterest" tabindex="-1" role="dialog" aria-labelledby="indicateInterest" aria-hidden="true" data-backdrop="false"  v-if="filtered_courses.length != 0" >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{current.Course_Name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-if="$store.state.authenticated">
                            <p>{{current.Course_Desc}}</p>
                            <button type ="button" class="btn btn-danger"  data-dismiss="modal" v-if="current.IUser_ID == $store.state.user_id" @click="removeInterest(current)">Remove Interest</button>
                            <button type ="button" class="btn interested"  data-dismiss="modal" v-else @click="addInterest()">Indicate Interest</button>
                        </div>
                        <p v-else>
                            Please log in <router-link to="/login">here</router-link>
                        </p>
                        
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
    // ALLCOURSESREGISTRATIONSTUDENT_URL, ALLCOURSESFILTEREDCOURSESTUDENT_URL,
    import { REGISTER_URL, DEREGISTER_URL, ADDINTEREST_URL, REMOVEINTEREST_URL} from '../../variables'
    export default{
        name:'YourClasses',
        data(){
            return{
                // registered: [],
                // indicated: [],
                current: []
            }
        },
        props:{
            registration: Array,
            filtered_courses: Array,
        },
        methods: {
            // getRegistrations(){
                
            //     axios.post(ALLCOURSESREGISTRATIONSTUDENT_URL,
            //     {
            //     },
            //     {withCredentials: true}
            //     ).then(response => {    
            //         if (response.data === 'no rights'){
            //             this.redirectToCourse()
            //         }else{           
            //             this.registered = response.data
            //         }
            //     })
            // },
            // getFilteredCourse(){
            //     axios.post(ALLCOURSESFILTEREDCOURSESTUDENT_URL,
            //     {},
            //     {
            //         withCredentials:true
            //     }
            //     ).then(response => {
            //         console.log(response)
            //         if (response.data === 'no rights'){
            //             this.redirectToCourse()
            //         }else {
            //             this.indicated = response.data
            //         }
                    
            //     })
            // },
            processInstructor(instructors){
                if (!instructors){
                    return instructors
                }
                return instructors.split(',').join(', ')
            },
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
                        // this.allCourses[this.current.Course_ID] = 'registered',
                        this.successMessage('Successfully registered')
                        this.$parent.getRegistrations()
                    }
                    
                })
            },            
            deregister(course){
                
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
            addInterest(){
                axios.post(ADDINTEREST_URL,{
                    'course_id': this.current.Course_ID,
                    'vote_start': this.current.Vote_Start,
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data === 'no rights'){
                        this.redirectToCourse()
                    }else{
                        this.successMessage('Successfully indicated interest')
                        this.$parent.getFilteredCourse()
                    }
                    
                })
            },
            removeInterest(course){
                axios.post(REMOVEINTEREST_URL,{
                    'course_id': course.Course_ID,
                    'vote_start': course.Vote_Start
                },                 
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data === 'no rights'){
                        
                        this.redirectToCourse()
                    }else{
                        this.successMessage('Successfully removed interest')
                        this.$parent.getFilteredCourse()
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
            // this.getRegistrations()
            // this.getFilteredCourse()
        },
        computed: {
            allCourses(){
                return this.registration.concat(this.filtered_courses)
            }
        }
    }
</script>
<style scoped>
    .badge-pill{
        width: 100px;
        cursor: pointer;
    }
    :deep(.registered){
        background-color: var(--dark-gray);
        color: white;
    }
    :deep(.register){
        background-color: var(--pill-green);
        color: white;
    }
    :deep(.indicated){
        background-color: var(--dark-gray);
        color: white;
    }
    :deep(.interested){
        background-color: var(--pill-yellow);
        color: white;
    }
</style>