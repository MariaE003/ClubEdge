    <?php
    use Twig\Environment;
    use Twig\Loader\FilesystemLoader;

    class BaseController{
        protected Environment  $twig;
        public function __construct(){
            $loader=new FilesystemLoader(__DIR__ . '/../views');
            $this->twig=new Environment($loader);
        }

        protected function render(string $view , array $data=[]):void{
            echo $this->twig->render($view,$data);
        }
    }
