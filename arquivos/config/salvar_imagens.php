<?php
include_once 'config.php';

session_start();
if (!isset($_SESSION['id_dono'])) {
    echo "Usuário não autenticado";
    exit();
}

$id_dono = $_SESSION['id_dono'];
$uploadDir = '../uploads/estabelecimentos/' . $id_dono . '/';


if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}


function salvarOuDeletarFoto($fotoInputName, $uploadDir, $pdo, $id_dono, $colunaBD)
{
    // Busca o caminho da foto atual no banco de dados
    $stmt = $pdo->prepare("SELECT $colunaBD FROM estabelecimentos WHERE id_dono = :id_dono");
    $stmt->execute([':id_dono' => $id_dono]);
    $fotoAtual = $stmt->fetchColumn();

    // Caso uma nova foto seja enviada
    if (isset($_FILES[$fotoInputName]) && $_FILES[$fotoInputName]['error'] === UPLOAD_ERR_OK) {
        // Exclui a foto atual se existir
        if ($fotoAtual && file_exists($fotoAtual)) {
            unlink($fotoAtual);
        }

        // Define o novo caminho para salvar a foto
        $fileName = basename($_FILES[$fotoInputName]['name']);
        $filePath = $uploadDir . $fotoInputName . '_' . time() . '_' . $fileName;

        // Move a nova foto para o diretório e retorna o caminho
        if (move_uploaded_file($_FILES[$fotoInputName]['tmp_name'], $filePath)) {
            return $filePath;
        }
    } else {
        // Se nenhum arquivo foi enviado e já existe uma foto, deletá-la
        if ($fotoAtual && file_exists($fotoAtual)) {
            unlink($fotoAtual);
        }

        return null;
    }
    return $fotoAtual;
}

// Salva cada foto e armazena o caminho na variável correspondente
$fotoPrincipal = salvarOuDeletarFoto('foto_principal', $uploadDir, $pdo, $id_dono, 'foto_principal');
$fotoAdicional1 = salvarOuDeletarFoto('foto_adicional_1', $uploadDir, $pdo, $id_dono, 'foto_adicional_1');
$fotoAdicional2 = salvarOuDeletarFoto('foto_adicional_2', $uploadDir, $pdo, $id_dono, 'foto_adicional_2');
$fotoAdicional3 = salvarOuDeletarFoto('foto_adicional_3', $uploadDir, $pdo, $id_dono, 'foto_adicional_3');
$fotoAdicional4 = salvarOuDeletarFoto('foto_adicional_4', $uploadDir, $pdo, $id_dono, 'foto_adicional_4');

try {
    // Atualiza os caminhos das fotos no banco de dados
    $stmt = $pdo->prepare("UPDATE estabelecimentos SET 
        foto_principal = :foto_principal, 
        foto_adicional_1 = :foto_adicional_1, 
        foto_adicional_2 = :foto_adicional_2, 
        foto_adicional_3 = :foto_adicional_3, 
        foto_adicional_4 = :foto_adicional_4 
        WHERE id_dono = :id_dono");

    $stmt->execute([
        ':foto_principal' => $fotoPrincipal,
        ':foto_adicional_1' => $fotoAdicional1,
        ':foto_adicional_2' => $fotoAdicional2,
        ':foto_adicional_3' => $fotoAdicional3,
        ':foto_adicional_4' => $fotoAdicional4,
        ':id_dono' => $id_dono
    ]);

    // Redireciona para perfil.php após salvar as fotos
    header("Location: ../perfil.php");
    exit();
} catch (PDOException $e) {
    echo "Erro ao salvar no banco de dados: " . $e->getMessage();
}
