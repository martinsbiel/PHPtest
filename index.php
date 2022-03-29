<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">   
        <title>CEP</title>
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="col-8">
                    <div class="card">
                        <div class="card-header">
                            <h2>CEP</h2>
                        </div>
                        <div class="card-body">
                            <p>Digite o CEP, apenas números:</p>
                            <form action="" method="post">
                                <div class="input-group mb-3">
                                    <input type="text" name="cep" class="form-control" maxlength="8" placeholder="CEP" aria-label="CEP" aria-describedby="cep" value="<?= $_POST['cep'] ?? null ?>">
                                    <input type="submit" class="btn btn-lg btn-secondary" id="cep" value="Consultar">
                                </div>
                            </form>
                            <?php
                                require_once 'config.php';

                                if(isset($_POST['cep'])){
                                    $cepNumber = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_NUMBER_INT);

                                    $cep = new CEP();
                                    $cep->setCEP($cepNumber);

                                    if($cep->checkAPIError()){
                                        echo '<div class="alert alert-danger" role="alert">O CEP solicitado não existe.</div>';
                                    } else if($cep->checkCEP($cepNumber)){
                                        // cep already in database
                                        $data = $cep->getCEPInfo();

                                        $details = $data->fetch(PDO::FETCH_OBJ);
                            ?>
                                        <ul>
                                            <li>CEP: <?= $details->cep ?></li>
                                            <li>Logradouro: <?= $details->logradouro ?></li>
                                            <li>Bairro: <?= $details->bairro ?></li>
                                            <li>Localidade: <?= $details->localidade ?></li>
                                            <li>UF: <?= $details->uf ?></li>
                                            <li>DDD: <?= $details->ddd ?></li>
                                        </ul>
                            <?php
                                    } else {
                                        // cep not in database
                                        $data = $cep->registerCEP();

                                        $details = $data->fetch(PDO::FETCH_OBJ);
                            ?>
                                        <ul>
                                            <li>CEP: <?= $details->cep ?></li>
                                            <li>Logradouro: <?= $details->logradouro ?></li>
                                            <li>Bairro: <?= $details->bairro ?></li>
                                            <li>Localidade: <?= $details->localidade ?></li>
                                            <li>UF: <?= $details->uf ?></li>
                                            <li>DDD: <?= $details->ddd ?></li>
                                        </ul>
                            <?php
                                    }
                                }
                            ?>
                        </div>
                        <div class="card-footer">
                            Criado por <a class="" href="https://github.com/martinsbiel">Gabriel Martins</a>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>