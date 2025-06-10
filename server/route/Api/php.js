import { NodePHP } from '@php-wasm/node';

export default async function handler(req, res) {
    const php = await NodePHP.load();
    const result = await php.run({
        code: '<?php echo "Hello from PHP!"; ?>',
    });
    res.status(200).send(result.body);
}