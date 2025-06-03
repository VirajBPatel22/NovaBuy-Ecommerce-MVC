<?php
Class Admin_Model_Csv{
    public function exportCsv($model)
    {
        $collection = $model->getCollection();
        $data = $collection->getData();

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="export.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Create output stream
        $output = fopen('php://output', 'w');

        // Add CSV headers
        fputcsv($output, array_keys($data[0]->getData()));
        
        foreach ($data as $order) {
            fputcsv($output,$order->getData());
        }

        // Close the stream
        fclose($output);
    }
}
?>