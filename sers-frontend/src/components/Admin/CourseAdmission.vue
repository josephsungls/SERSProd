<template>
    <div class="container-fluid  display-container">
        <span class='back'>
            <button type='button' @click="goBack()"><i class="fas fa-long-arrow-alt-left"></i></button>
        </span>
        <h4>{{selectedCourse.Course_ID}}: {{selectedCourse.Course_Name}} - Class {{selectedCourse.Class}}</h4>
        <table class ="mt-3">
            <tr>
                <th>Instructor:</th> 
                <td><span v-for="(instructor, idx) in instructors" :key="idx">{{instructor.User_Name}}<span v-if="idx!=(instructors.length-1)">, </span> </span></td>
            </tr>
            <tr>
                <th>Date of course:</th> 
                <td>{{selectedCourse.Run_StartDate}}</td>
            </tr>
            <tr>
                <th>Slots Registered:</th> 
                <td>{{selectedCourse.No_Registrations}}/{{selectedCourse.Run_MaxSlots}}</td>
            </tr>
            <tr>
                <th>Registration End Date:</th>
                <td>{{selectedCourse.Registration_End}}</td>
            </tr>
            <tr>
                <th>Registration Status:</th>
                <td>{{selectedCourse.Run_Status}}</td>
            </tr>
        </table>
        <div v-if="$store.state.authenticated && $store.state.role == 2">
        <p class="mt-3"><b>Feedback Link:</b></p>
        <input type="text" disabled :value = "feedbackURL" class="feedbackURL" >
        </div>
        <!-- <p class = "user-select-all">{{feedbackURL}}</p> -->
        <p class="mt-3"><b>Export Status:</b></p>
        <select class="form-select" v-model="exportStatus">
            <option value = "all">All</option>
            <option value= "accepted">Accepted</option>
            <option value="rejected">Rejected</option>
        </select>
        <br/>
     
        <button type = "button" class = "btn btn-primary mt-3 " @click="generateCSV(exportStatus)">
            Export student list to CSV
        </button>        
        <button type = "button" class = "btn btn-primary mt-3 ml-2" :disabled="selectedCourse.Feedback_Count? false:true" @click="viewFeedback()">
            View Feedback
        </button>        
        <div class="table-container">
            <div class="table-header">
                <h4>Student List</h4>
                <p class = "text-muted">Students who registered for the course.</p>
            </div>

            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">#</th>
                        <th scope = "col">Student Name</th>
                        <th scope = "col">Student Email</th>
                        <th scope = "col">Status</th>
                        
                    </tr>
                </thead>
                <tbody v-if="registrants.length!=0">
                    <tr v-for="registrant in registrants" :key="registrant" @click="change(registrant)" data-toggle="modal" data-target="#updateStudentRegistration">
                        <td>{{registrant.User_ID}}</td>
                        <td>{{registrant.User_Name}}</td>
                        <td>{{registrant.User_Email}}</td>
                        <td>
                            <span :class="['badge', 'badge-pill', registrant.Reg_Status]">
                                <span>{{registrant.Reg_Status}}</span>
                            </span>
                        </td>

                    </tr>
                </tbody>                
                <tbody v-else>
                    <tr>
                        <div class="table-header"><h4>No registrants at the moment</h4></div>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="updateStudentRegistration" tabindex="-1" role="dialog" aria-labelledby="updateStudentRegistration" aria-hidden="true" data-backdrop="false" v-if="registrants.length!=0 && $store.state.role == 2">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{current.User_ID}} - {{current.User_Name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <button type ="button" class="btn btn-success"  data-dismiss="modal" @click="changeRegistrationStatus('accepted')">Accept</button>
                        <button type ="button" class="btn btn-danger ml-3"  data-dismiss="modal"  @click="changeRegistrationStatus('rejected')">Reject</button>
                    </div>
                </div> 
            </div>
        </div>

    </div>
</template>
<script>
    import axios from "axios"
    import Swal from 'sweetalert2'
    import {GETRUNREGISTRATIONS_URL, CHANGEREGISTRATIONSTATUS_URL, GETRUNINSTRUCTOR_URL} from "../../variables"
    export default{
        name: "IndividualCourse",
        data(){
            return{
                registrants: [],
                current: '',
                instructors: [],
                exportStatus: 'all',
                feedbackID: 1,
                feedbackURL: ''
            }
        },

        props:{
            selectedCourse: Object
        },
        methods:{
            getRegistrants(){

                    axios.get(GETRUNREGISTRATIONS_URL,
                    {
                        params: {
                            'course_id': this.selectedCourse.RunCourse_ID,
                            'run_start': this.selectedCourse.Run_StartDate,
                            'class': this.selectedCourse.Class
                        },
                        withCredentials: true
                    }
                    ).catch(function(error){
                        console.log(error)
                        return
                    })
                    .then(response=>{
                        console.log(response.data)
                        if (response.data){
                            this.registrants = response.data
                            this.current = this.registrants[0]
                        }
                    })


            },
            change(registrant){
                this.current = registrant
            },
            goBack(){
                this.$parent.back()
            },
            changeRegistrationStatus(status){
                axios.post(CHANGEREGISTRATIONSTATUS_URL,{
                    'user_id': this.current.User_ID,
                    'course_id': this.selectedCourse.RunCourse_ID,
                    'run_start': this.selectedCourse.Run_StartDate,
                    'class': this.selectedCourse.Class,
                    'status': status
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data == 'updated'){
                        Swal.fire({
                            icon: 'success',
                            title: 'Status updated',
                            showConfirmButton: false,
                            timer: 1000
                        })
                        this.getRegistrants()
                    }
                    
                })
            },
            getRunInstructor(){
                axios.post(GETRUNINSTRUCTOR_URL,{
                    'course': this.selectedCourse,
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    console.log('hi', response)
                    this.instructors = response.data
                    
                })

            },
            generateCSV(status){
                var filtered_students = this.registrants
                if (this.exportStatus != 'all'){
                    filtered_students = this.registrants.filter(function(row){
                        if (row.Reg_Status == status){
                            return (row)
                        }
                    })
                }

                const data = filtered_students.map(row=>({
                    User_ID: row.User_ID,
                    User_Name: row.User_Email,
                    User_Email: row.User_Email,
                    Reg_Status: row.Reg_Status,
                    Completion_Status: row.Completion_Status,
                    Reg_DateTime: row.Reg_DateTime,
                    RegCourse_ID: row.RegCourse_ID,
                    RClass: row.RClass,
                    RRun_StartDate: row.RRun_StartDate,
                }))

                const csvText = this.objectToCSV(data)
                this.downloadCSV(csvText)
            },
            objectToCSV(data){
                const csvRows = []
                console.log(data)
                console.log(Object.keys(data[0]))

                const headers = Object.keys(data[0])

                csvRows.push(headers.join(','))
                console.log(csvRows)

                for (const row of data){
                    const values = headers.map(header=>{
                        const escaped = (''+row[header]).replace(/"/g, '\\"')
                        return `"${escaped}"`
                    })
                    // console.log(csvRows)
                    csvRows.push(values.join(','))
                    console.log(csvRows)
                    
                }
                console.log(csvRows.join('\n'))
                return csvRows.join('\n')
            },
            downloadCSV(data){
                const blob = new Blob([data], {type: 'text/csv'})
                const url = window.URL.createObjectURL(blob)
                const a = document.createElement('a')
                a.setAttribute('hidden', '')
                a.setAttribute('href', url)
                a.setAttribute('download', this.registrants[0].RegCourse_ID+ "-" + this.registrants[0].RRun_StartDate + "-" + this.registrants[0].RClass + "-" + this.exportStatus + '.csv' )
                document.body.appendChild(a)
                a.click()
                document.body.removeChild(a)

            },
            viewFeedback(){
                this.$parent.viewFeedback()
            }
        },
        mounted(){
            this.getRegistrants()
            this.getRunInstructor()
            this.feedbackURL = window.location.origin + '/feedback/' + this.selectedCourse.RunCourse_ID + '/' + this.selectedCourse.Run_StartDate + '/' + this.selectedCourse.Class + '/' + this.feedbackID
        }
    }
</script>
<style>
    .badge{
        width:100px;
    }
    label{
        font-weight:bold;
    }
    #course-id-alert{
        color:var(--pill-red);
    }
    .back button{
        background-color: var(--navigation-color);
        border: none;
        font-size: 2em;
        transition:0.2s;
    }
    .back button:hover{
        color: var(--button-color);
    }
    .back button:focus{
        outline:none;
        box-shadow:none;
    }

    td{
        padding-left: 20px;
    }
    .pending{
        background-color: var(--gray);
        color: white;
    }
    .accepted{
        background-color: var(--pill-green);
        color: white;
    }
    .rejected{
        background-color: var(--pill-red);
        color: white;
    }
    tbody td{
        cursor: pointer;
    }
    tbody tr{
        transition: 0.2s;
    }
    tbody tr:hover{
        background-color: var(--button-color);
        color: white;
    }
    .feedbackURL {
        width: 450px;
    }
    .table-container{
        padding-top: 10px;
    }
</style>