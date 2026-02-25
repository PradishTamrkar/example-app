import { useState } from 'react'
import { router } from '@inertiajs/react'
import { route } from 'ziggy-js'
import { useFormState } from 'react-dom'

export default function Index({ students, search }) { //students is prop value obtained from backend where students is an array of student
    const [form, setForm] = useState({ student_name:'', student_email:'', phone_no:'' }) //const [current value, value to update] = useState(initial value)
    const [editId, setEditId] = useState(null) //editId is null by default , when we click on edit option in a student the editId gets the id of that student
    const [searchStudents, setSerchStudents] = useState(search ?? '')


    const handleSubmit = (e) => {
        e.preventDefault() //e vaneko event ani when we submit a form, brower le full-page reload garna khojcha ani e.preventDefault() le chei prevents the brower from page reload

        //if there is editId, router/inertia sends a put http request
        if(editId){
            router.put(route('students.update', editId), form, { //route le /student/{id} dincha ani form vaneko requested input vayo ani form sucess vayechi setForm le feri brings the form back to initial state
                onSuccess: () => {
                    setForm({ student_name:'', student_email:'', phone_no:'' })
                    setEditId(null)
                }
            })
        } else {
            router.post(route('students.store'), form, { //edit id chaina vane router/inertia le post request pathauha & then route gives /students and then again on sucess setForm le initial state ma lagdincha
                onSuccess: () => setForm({ student_name:'', student_email:'', phone_no:'' })
            })
        }
    }

    const handleEdit = (student) => {
        setEditId(student.student_id) //sets the edit id equad to the stduent id
        setForm({ //so yo handelEdit vanne function ma chei if edit button thichyo vane tyo editid ma ako studentid bata veteko info chei form ma load huncha
            student_name: student.student_name,
            student_email: student.student_email,
            phone_no: student.phone_no
        })
    }

    const handleDelete = (id) => {
        if(confirm('Are you sure?')) { //if confirm vaneko brower gives a pop up if you want to delete or not if ok(true) delete or if cancel(false) return back
            router.delete(route('students.destroy', id)) //router.delete le http delete request send garcha and then route le feri /student/{id} dincha ani tya delete operation
        }
    }

    const handleSearch = (e) => {
        const searchStudents = e.target.value;
        setSerchStudents(searchStudents)

        router.get(route('students.index'),
            {search: searchStudents},
            {preserveState: true}
    )
    }

    return (
        <div>
            <h1>Student List</h1>
            <input type='text' value={searchStudents} onChange={handleSearch} placeholder='Search Students'/>
            <form onSubmit={handleSubmit} style={{marginBottom:'20px'}}> {/*form submit vayechi handleSubmit vanne fn call huncha*/ }
                <input value={form.student_name} onChange={e=>setForm({...form, student_name:e.target.value})} placeholder='NAME' /> {/* ... vaneko spread operator which copies all the values insides the fields */}
                <input value={form.student_email} onChange={e=>setForm({...form, student_email:e.target.value})} placeholder='EMAIL' /> {/* ...form le chei form bhitra vako fields haru ko data copy garcha setForm le null value assign garye pani ...form le copy garirako data chei tyo fields haur ma aucha  */}
                <input value={form.phone_no} onChange={e=>setForm({...form, phone_no:e.target.value})} placeholder='CONTACT' />{/* like first ma form: name:"" , email:"", phone:"", hamle paila phone bharyo rey then that gets copies into ...form bhitra ko  phone field and form ko phone field but hamle aba email bharnu paro rey teti bela feir setForm use huncha but setForm le ta initial vlaue dine ho teti bela ...form ma vako data chei ayera form bhitra bascha */}
                <button type='submit'>{editId ? 'Update' : 'Add'} Student</button> {/*yo ta normal conditional operator ho editid null cha vane Add Student dhekaune editId cha vane Update student dhekaune */}
            </form>
            <table border={1} cellPadding={5}>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {students.data.map(s => (
                        <tr key={s.student_id}>
                            <td>{s.student_name}</td>
                            <td>{s.student_email}</td>
                            <td>{s.phone_no}</td>
                            <td>
                                <button onClick={()=>handleEdit(s)}>Edit</button>
                                <button onClick={()=>handleDelete(s.student_id)}>Delete</button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>

            <div style={{marginTop:'10px'}}>

                {students.prev_page_url && <button onClick={() => router.visit(students.prev_page_url)}>Prev</button>}
                {Array.from({ length:students.last_page},(_,i)=>i+1).map(page=>(
                    <button key={page} onClick={()=> router.get(route('students.index'),{search: searchStudents, page: page})} style={{fontWeight: students.current_page === page ? 'bold' : 'normal', textDecoration: students.current_page === page ? 'underline' : 'none', margin: '0 3px'}}>{page}</button>
                ))}
                {students.next_page_url && <button onClick={() => router.visit(students.next_page_url)}>Next</button>}
                <span style={{marginLeft:'10px'}}>
                    Page {students.current_page} of {students.last_page}
                </span>
            </div>
        </div>
    )
}
