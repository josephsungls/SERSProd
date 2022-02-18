<template>
    <div>
        <div class="table-container">
            <table class = "table sortable">
                <thead>
                    <tr>
                        <th scope = "col">Course Code & Name</th>
                        <th scope = "col">Course Description</th>
                        <th scope = "col">Category</th>
                        <th scope = "col">Status</th>
                        <th scope = "col" class = "status">Course Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="course in registrations" :key="course">
                        <td  @click="enterCourse(course)"><b>{{course.Course_ID}}</b> <br> {{course.Course_Name}} - Class:{{course.Class}}</td>
                        <td  @click="enterCourse(course)"> {{course.Run_Desc}}</td>
                        <td  class="middle-of-row" @click="enterCourse(course)">{{course.Category}}</td>
                        <td  class="middle-of-row" @click="enterCourse(course)">
                            <span :class="['badge', 'badge-pill', course.Instructor_ID == null? 'gray':course.Run_Status, 'instructor-pill', 'centerme']">
                                <span v-if="course.Instructor_ID == null">Assign Instructor</span>
                                <span v-else-if="course.Run_Status == 'pending'">WAITING FOR INSTRUCTOR</span>
                                <span v-else>INSTRUCTOR READY</span>
                            </span>
                        </td>
                        <td class = "text-center middle-of-row">
                            <span :class="['badge', 'badge-pill', course.Run_Status== 'pending'? 'gray':'updated']" data-toggle="modal" data-target="#confirmOpenRegistration" @click="changeCurrent(course)">Open</span>
                            
                        </td>
                        
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="confirmOpenRegistration" tabindex="-1" role="dialog" aria-labelledby="confirmOpenRegistration" aria-hidden="true" data-backdrop="false">
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
                        <button type ="button" class="btn btn-success mr-2"  @click="openRegistrationConfirmation()" data-dismiss="modal" v-if="current.FCourse_Status != 'offered'">Open Registration</button>
                        
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
    import {PENDINGREGISTRATIONS_URL, OPENREGISTRATION_URL} from '../../variables'
    export default{
        name: 'PendingRegistration',
        data(){
            return {
                current: [],
                registrations: [],
                registration_end: ''
            }
        },
        methods:{
            getOngoingRegistrations(){
                axios.get(PENDINGREGISTRATIONS_URL).then(response => {
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
            openRegistration(){
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
                    }else if(response.data == "updated"){
                        Swal.fire({
                            icon: 'success',
                            title: 'Course is now open for registration!',
                        })
                        this.getOngoingRegistrations()
                    }
                    
                })
                
                
            },
            changeCurrent(course){
                this.current = course
                this.registration_end = course.Registration_End
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
                }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    router.push('/courses')
                }
                })
            },
            openRegistrationConfirmation(){
                Swal.fire({
                title: 'Are you sure?',
                text: "By confirming, you can still edit the details of the course but not the instructor. The course will be release for students to register!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Open!'
                }).then((result) => {
                if (result.isConfirmed) {
                    this.openRegistration()
                }
                })
            }            
        },
        mounted(){
            this.getOngoingRegistrations()
            let recaptchaScript = document.createElement('script')
            recaptchaScript.setAttribute('src', 'https://www.kryogenix.org/code/browser/sorttable/sorttable.js')
            document.head.appendChild(recaptchaScript) 
        }
        
    }
</script>
<style scoped>
    thead{
        position:sticky;
        top:0;
        background-color: white;
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

    /* colors here still need to be fixed, and according to the various values  */
    :deep(.pending){
        background-color: #5A959A;
        color: white;
    }
    :deep(.updated){
        background-color: var(--pill-green);
        color: white;
    }
    :deep(.gray){
        /* background-color: var(--gray); */
        background-color: #5A959A;
        color: white;
    }
    
</style>