<?php
function koneksi()
{
  return  mysqli_connect('localhost', 'root','', 'latihanphp');  
}
function query($query)
{
  $conn = koneksi();
  $result = mysqli_query($conn, $query);
  //jika hasilnya 1

  if (mysqli_num_rows($result) == 1){
    return mysqli_fetch_assoc($result);
  }
  $rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;

}
return $rows;
}

function tambah($data)
{
    $conn = koneksi();

    $nama = htmlspecialchars($data['NAMA']);
    $npm = $data['NPM'];
    $email = $data['EMAIL'];
    $jurusan = $data['JURUSAN'];
    $gambar = $data['GAMBAR'];
    $query = "INSERT INTO
                mahasiswa
                VALUES
                (null, '$nama','$npm','$email','$jurusan','$gambar');
                ";


    mysqli_query($conn, $query);   
    echo mysqli_error($conn);
    return mysqli_affected_rows($conn);
}

function hapus($id) {
    $conn = koneksi();
    mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id");
    return mysqli_affected_rows($conn);
}

function ubah($data)
{
    $conn = koneksi();

    $id = $data['id'];
    $nama = htmlspecialchars($data['NAMA']);
    $npm = $data['NPM'];
    $email = $data['EMAIL'];
    $jurusan = $data['JURUSAN'];
    $gambar = $data['GAMBAR'];
    $query = "UPDATE mahasiswa SET
                NAMA = '$nama',
                NPM = '$npm',
                EMAIL = '$email',
                JURUSAN = '$jurusan',
                GAMBAR = '$gambar'
                WHERE id = $id";


    mysqli_query($conn, $query);   
    echo mysqli_error($conn);
    return mysqli_affected_rows($conn);
}

function cari($keyword) {
    $conn = koneksi();
    $query = "SELECT * FROM mahasiswa
                WHERE 
                NAMA LIKE '%$keyword%' OR 
                NPM LIKE '%$keyword%'
                ";

    $result = mysqli_query($conn, $query);          
              $rows = [];
                 while ($row = mysqli_fetch_assoc($result)) {
                     $rows[] = $row;
}
    return $rows;
}

function login($data) 
{
    $conn = koneksi();
    $username = htmlspecialchars($data['username']);
    $password = htmlspecialchars($data['password']);
//cek username
    if ($user =query("SELECT * FROM user WHERE username = '$username'")) {
        //cek password
        if(password_verify($password, $user['password']))
        //set session
        $_SESSION['login'] = true;
        header("Location: index.php");
        exit;
    }
        return [
            'error' =>true,
            'pesan' => 'USERNAME DAN PASSWORD SALAH!!!'
        ];
    
}

function registrasi($data)
 {
    $conn = koneksi();
    $username = htmlspecialchars(strtolower($data['username']));
    $password1 = mysqli_real_escape_string($conn, $data['password1']);
    $password2 = mysqli_real_escape_string($conn, $data['password2']);
    
    if(empty($username) || empty($password1) || empty($password2)) {
        echo "<script>
        alert('username / password tidak boleh kosong!');
        document.location.href = 'registrasi.php';
        </script>";
        return false;
    }
    //jika username sudah ada
    if (query("SELECT * FROM user WHERE username = '$username'")){
        echo "<script>
        alert('username sudah ada !!!');
        document.location.href = 'registrasi.php';
        </script>";
        return false;
        }
        //jika pass tidak sesuai
        if($password1 !== $password2) {
            echo "<script>
            alert('pass tidak sesuai !!!');
            document.location.href = 'registrasi.php';
            </script>";
            return false;
        }
        //jika sudah sesuai tinngal eksripsi
        $password_baru = password_hash($password1, PASSWORD_DEFAULT);
        //insert ke table user
        $query = "INSERT INTO user
        VALUES
        (null, '$username', '$password_baru')
        ";
        mysqli_query($conn, $query);
       return mysqli_affected_rows($conn);
}
?>
