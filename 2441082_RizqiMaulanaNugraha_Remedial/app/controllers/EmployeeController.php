public function index(): void {
        $this->view('employees/index', [
        'title' => 'Data Employee',
        'employees' => $this->model('Employee')->all(),
        ]);
    }
    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('employees');
        $input = array_map('trim', $_POST);
        if (empty($input['name'])) {
        $this->flash('danger', 'Nama pekerja wajib diisi.');
        $this->redirect('employees/create');
    }
    $this->model('Employee')->create([
    'nip' => $input['nip'],
    'name' => $input['name'],
    'department' => $input['department'],
    'position' => $input['position'],
    'salary' => $input['salary'],
    ]);
    $this->flash('success', 'Pekerja berhasil ditambahkan.');
    $this->redirect('employees');
}