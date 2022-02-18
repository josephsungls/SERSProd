<template>
    <div class = "container-fluid  display-container">
        <div class="table-top">
            <div class="table-desc">
                <h4>Course Database</h4>
                <p class = "text-muted"><i>Create and update courses.</i></p>
            </div>
            <button type="button" class ="add-course ml-5">
                <router-link to="/addcourse">
                    <i class="fas fa-plus"></i>Add New Course
                </router-link>
            </button>
        </div>
        <div class="table-container">

            <table class = "table sortable" id="myTable">
                <thead>
                    <tr>
                        <th scope = "col">Course Code & Name</th>
                        <th scope = "col">Course Description</th>
                        <th scope = "col">Category</th>
                        <th scope = "col" class = "status text-center">Course Status</th>
                        <th scope ="col" style="width: 8%" class = "text-center">View Runs</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="course in courses_data" :key="course">
                        <td class="update" @click="changeUpdate(course)" data-toggle="modal" data-target="#updateCourse"><b>{{course.Course_ID}}:</b><br/>{{course.Course_Name}}</td>
                        <td class="update expand" @click="changeUpdate(course)" data-toggle="modal" data-target="#updateCourse">{{course.Course_Desc}}</td>
                        <td class="update middle-of-row" @click="changeUpdate(course)"  data-toggle="modal" data-target="#updateCourse">{{course.Category}}</td>
                        <td class = "text-center middle-of-row">
                            <span :class="['badge', 'badge-pill', course.Course_Status, 'more']"  @click="changeStatus(course)" data-toggle="modal" data-target="#updateStatus">{{course.Course_Status.toUpperCase()}}</span>
                        </td>

                        <td class ="update middle-of-row text-center eye" @click="changeView(course.Course_ID)">
                            <i class="fas fa-eye"></i>
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>

        <!--Update status Modal -->
        <div class="modal fade" id="updateStatus" tabindex="-1" role="dialog" aria-labelledby="updateStatus" aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{current.Course_Name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="current.Course_Status != 'voting' && current.Course_Status != 'registering'">
                        <h5>Change Status:</h5>
                        <!-- <button type="button" class="btn active modal-btn" @click="updateStatus('active')" v-if="current.Course_Status!='active'"  data-dismiss="modal">Active</button> -->
                        <button type="button" class="btn voting modal-btn" @click="openCourse('voting')"  data-dismiss="modal">Voting</button>
                        <button type="button" class="btn registering modal-btn" @click="openCourse('registration')"  data-dismiss="modal">Registering</button>
                        <button type="button" class="btn not offered modal-btn" @click="updateStatus('not offered')" v-if="current.Course_Status!='not offered'"  data-dismiss="modal">Not Offered</button>
                        <button type="button" class="btn retired modal-btn" @click="updateStatus('retired')" v-if="current.Course_Status!='retired'"  data-dismiss="modal">Retired</button>

                    </div>
                    <div class="modal-body" v-if="current.Course_Status == 'voting'">
                        <h5>Close Voting campaign</h5>
                        <button type ="button" class="btn btn-success"  data-dismiss="modal"   @click="update('offered')">Open Registration</button>
                        <button type ="button" class="btn btn-danger ml-2"  data-dismiss="modal"  @click="update('closed')">Close Campaign</button>

                    </div>
                    <div class="modal-body" v-if="current.Course_Status == 'registering'">
                        <h5>Close registration</h5>
                        <button type ="button" class="btn btn-success"  data-dismiss="modal" @click="openCourse('registration')">Open Additional Class</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update course Modal -->
        <div class="modal fade" id="updateCourse" tabindex="-1" role="dialog" aria-labelledby="updateCourse" aria-hidden="true" data-backdrop="false" v-if="current_update!=[]">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{current.Course_Name}} - {{current.Course_ID}}</h5>
                        <button type="button" class="close" id = "close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body px-lg-4 pb-lg-4">
                        <h5>Update Course:</h5>
                        <!-- <button type="button" class="btn active modal-btn" @click="updateStatus('active')" v-if="current.Course_Status!='active'"  data-dismiss="modal">Active</button> -->
                        <form>
                            <div class="form-group">
                                <label for="course-id">Course ID</label>
                                <input type="text" class="form-control" id="course-id" placeholder="Course ID" v-model="current_update.Course_ID">
                                <small id="course-id-alert" class="form-text" v-if="alert != ''">Course ID already exists</small>
                            </div>


                            <div class="form-group">
                                <label for="course-name">Course Name</label>
                                <input type="text" class="form-control" id="course-name" placeholder="Course Name"  v-model="current_update.Course_Name">
                            </div>
                            <label for="course-desc">Course Description</label>
                            <textarea class="form-control" rows="10" id="course-desc" placeholder="Course Description🖊️"  v-model="current_update.Course_Desc"></textarea>
                            <br/>
                            <label for="course-cat" >Course Category</label>
                            <select class="form-control" id="course-cat"  v-model="current_update.Category">
                                <option>Basic</option>
                                <option>Intermediate</option>
                                <option>Advance</option>
                            </select>
                            <br/>
                            <button type="button" class="btn btn-primary" @click="updateCourse()">Update Course</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import Swal from 'sweetalert2'
    import axios from 'axios'
    import router from '../../router'
    // import searchFunction from '../../assets/search.js'

    import {UPDATESTATUS_URL, COURSES_URL, OPENVOTING_URL, CREATEREGISTRATION_URL, UPDATECOURSE_URL, OFFERSINGLECAMPAIGN_URL, CLOSESINGLECAMPAIGN_URL} from '../../variables'
    export default{
        name: "CourseDB",
        data(){
            return{
                current: [],
                courses_data: [],
                current_update: [],
                alert: '',

            }
        },
        props:{
            courses : Array
        },
        methods:{
            changeStatus(course){
                this.current = course

            },
            changeUpdate(course){
                this.current = course
                this.current_update = {...course}

            },
            updateStatus(status){

                axios.post(UPDATESTATUS_URL,{
                    'course_id': this.current.Course_ID,
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
                    this.$parent.getCourses()
                    this.getCourses()
                    document.getElementById('close').click()

                })
            },
            openCourse(type){
                var API_URL
                type == 'voting'? API_URL = OPENVOTING_URL : API_URL = CREATEREGISTRATION_URL
                axios.post(API_URL,{
                    'course_id': this.current.Course_ID,
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
                    this.notify(type)
                    this.getCourses(),
                    document.getElementById('close').click()

                })
            },
            getCourses(){
                axios.get(COURSES_URL).then(response => (
                    this.courses_data = response.data
                ))
            },
            updateCourse(){
                axios.post(UPDATECOURSE_URL,{
                    'original_id': this.current.Course_ID,
                    'course_id': this.current_update.Course_ID,
                    'course_name': this.current_update.Course_Name,
                    'course_desc': this.current_update.Course_Desc,
                    'course_cat': this.current_update.Category,
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

                    else if (response.data == "Course ID exists"){
                        Swal.fire({
                        icon: 'error',
                        title: 'Course ID already exists!',
                        })
                    }
                    else if (response.data == "updated"){

                        this.getCourses()
                        document.getElementById('close').click();

                        Swal.fire({
                        icon: 'success',
                        title: 'Course details changed!',
                        })
                    }

                })
            },
            // HERE
            update(status){
                var url = ""
                status == "offered"? url = OFFERSINGLECAMPAIGN_URL: url = CLOSESINGLECAMPAIGN_URL
                axios.post(url,{
                    'course_id': this.current.Course_ID,
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
                    if (status == "offered"){
                        this.notify('registration')
                    }else{
                        this.notify('closed')
                    }
                    this.getCourses(),
                    document.getElementById('close').click()

                })
            },
            changeView(course_id){
                router.push('/coursehistory/'+ course_id)
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
            notify(type){
                if (type == 'voting'){
                    Swal.fire({
                        icon: 'success',
                        title: 'Course is now opened for voting!',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }else if (type == 'registration'){
                    Swal.fire({
                        icon: 'success',
                        title: 'Course opened!',
                        text: 'Remember to assign instructor(s) to conduct the course!',
                    })
                }else{
                    Swal.fire({
                        icon: 'success',
                        title: 'Voting campaign has been closed!',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            }
        },
        mounted(){
            this.getCourses()
            let recaptchaScript = document.createElement('script')
            recaptchaScript.setAttribute('src', 'https://www.kryogenix.org/code/browser/sorttable/sorttable.js')
            document.head.appendChild(recaptchaScript)
        }
    }
</script>
<style scoped>
    thead{
        position: sticky;
        top:0;
        background-color: white;
        
    }
    .table-container{
        padding: 0px;
    }
    
    .table-top {
        padding: 0px 8px;
        display: inline-block;
        width: 100%; 
        white-space: nowrap;
        overflow-x:auto;
    }
    .table-desc{
        float:left;
    }

    
    tbody tr:hover {
        background-color: rgba(78, 47, 49, 0.767);
    }

    /* float middle of row (vertically) */
    .middle-of-row{
        vertical-align: middle;
        text-align: center;
    }

    .modal-btn{
        transition:0.2s;
        
        text-align: center;
        cursor: pointer;
    }

    .modal-btn:hover{
        transform: translateY(-2px);
        
    }

    .add-course {
        background-color: white; /* Blue background */
        border: 1px solid; /* Remove borders */
        border-radius: 4px;
        padding: 12px 16px; /* Some padding */
        font-size: 16px; /* Set a font size */
        cursor: pointer; /* Mouse pointer on hover */
        position: relative;
        transition: .2s transform ease-in-out;
        will-change: transform;
        z-index: 0;
        float:right;
    }

    .add-course a {
        text-decoration: none;
        color: black;
    }

    .add-course i {
        padding-right: 10px;
    }

    .add-course:hover::after {
        transform: translate(0,0);
    }
    .add-course:hover {
        background-color: rgba(78, 47, 49, 0.767);
        color: white;
        transform: scale(1.05);
        will-change: transform;
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 10px;
    }
    .add-course:hover a {
        color: white;
    }

    .badge-pill{
        padding: 9px;
        border-radius: 50px;
        word-wrap: break-word;
    }

    .badge-pill::after{
        box-shadow: 2px;
    }

    :deep(.active){
        /* background-color: var(--pill-green); */
        background-color: #29CC97;
        color: white;
    }
    :deep(.voting){
        /* background-color: var(--pill-blue); */
        background-color: #6495ED;
        color: white;
    }
    :deep(.registering){
        /* background-color: var(--pill-yellow); */
        background-color: #FFF380;
        color: black;
    }
    :deep(.not.offered){
        /* background-color: var(--gray); */
        background-color: #C21E56;
        color: white;
    }
    :deep(.retired){
        /* background-color: var(--pill-red); */
        background-color: rgb(71, 71, 71);
        color: white;
    }

    .update{
        cursor: pointer;
        /* transition:0.2s; */
    }

    .eye:hover{
        font-size:1.5em;
    }

</style>