<?php
namespace App\Controller;

use App\Application\ConfigApplication;
use App\Entity\Task;
use App\Entity\TaskHistory;
use Exception;
use SebastianBergmann\CodeCoverage\Report\Html\Facade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ConfigController
 * @Route("/config")
 * @package App\Controller
 */
class ConfigController extends AbstractController
{
    public function home()
    {
        return $this->redirectToRoute('sonata_admin_dashboard');
    }
    /**
     * @Route("/generatePhpConfig", name="generatePhpConfig")
     * @param ConfigApplication $configApplication
     * @return Response
     */
    public function generatePhpConfig(ConfigApplication $configApplication): Response
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findAll();
        $taskConfigs = [];
        /** @var Task $task */
        foreach ($tasks as $task) {
            $taskConfigs[$task->getId()] = [
                'id' => $task->getId(),
                'name' => $task->getName(),
                'host' => $task->getHost(),
                'dir' => $task->getDir(),
                'status' => $task->getStatus(),
            ];
        }
        $configContents = $configApplication->writeConfig($taskConfigs);
        return $this->render('config/view.html.twig', [
            'configContents' => $configContents
        ]);
    }

    /**
     * @Route("/viewReport/{id}", name="viewReport")
     * @param $id
     * @return Response
     */
    public function viewReport($id): Response
    {
        /** @var TaskHistory $report */
        $report = $this->getDoctrine()->getRepository(TaskHistory::class)->find($id);
        return $this->render('config/view_report.html.twig', [
            'url' => '/reports' . $report->getReportDir() . '/index.html'
        ]);
    }

    /**
     * @Route("/generateReport/{id}", name="generateReport")
     * @param string $id
     * @param ConfigApplication $configApplication
     * @return RedirectResponse
     * @throws \Exception
     */
    public function generateReport(string $id, ConfigApplication $configApplication)
    {
        $libConfigs = $configApplication->getLibConfig();
        if (!isset($libConfigs['tasks'][$id])) {
            throw new Exception('任务不存在');
        }

        $taskDataFile = $libConfigs['dataDir'] . '/' . $id . '.xzpcc';
        if (is_file($taskDataFile)) {
            $content = file_get_contents($taskDataFile);
            if (!empty($content)) {
                $repo = $this->getDoctrine()->getRepository(Task::class);
                /** @var Task $task */
                $task = $repo->find($id);
                $task->setStatus('process');
                $this->getDoctrine()->getManager()->flush();

                $coverage = unserialize($content);
                $reportDir = $libConfigs['reportDir'] . '/' . $id . '/' . date('Y-m-d-H-i-s');
                if (!is_dir(dirname($reportDir))) {
                    if (!mkdir($concurrentDirectory = dirname($reportDir)) && !is_dir($concurrentDirectory)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
                    }
                }
                if (!is_dir($reportDir)) {
                    if (!mkdir($reportDir) && !is_dir($reportDir)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $reportDir));
                    }
                }
                $writer = new Facade;
                $writer->process($coverage, $reportDir);

                $task->setStatus('run');
                $this->getDoctrine()->getManager()->flush();
                unlink($taskDataFile);

                $report = new TaskHistory();
                $report->setTask($task);
                $report->setReportDir(str_replace($libConfigs['reportDir'], '', $reportDir));
                $report->setCreateAt(new \DateTime());
                $this->getDoctrine()->getManager()->persist($report);
                $this->getDoctrine()->getManager()->flush();
            } else {
                throw new Exception('未采集到数据');
            }
        } else {
            throw new Exception('未生成采集数据文件');
        }
        return $this->redirectToRoute('admin_app_taskhistory_list');
    }
}